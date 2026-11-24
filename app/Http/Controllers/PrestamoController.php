<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Lector;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrestamoController extends Controller
{
    public function index()
    {
        // Si es admin, ve todos los préstamos
        if (auth()->user()->role == 'admin') {
            $prestamos = Prestamo::with(['libro', 'lector'])->latest()->paginate(10);
        } else {
            // Si es lector, solo ve los préstamos vinculados a su correo
            $prestamos = Prestamo::with(['libro', 'lector'])
                ->whereHas('lector', function($query) {
                    $query->where('email', auth()->user()->email);
                })->latest()->paginate(10);
        }
        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        // ✅ SEGURIDAD: Solo el admin puede registrar préstamos
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo el bibliotecario puede registrar préstamos.');
        }

        $libros = Libro::where('stock', '>', 0)->get();
        $lectores = Lector::all();
        return view('prestamos.create', compact('libros', 'lectores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            // Ya no validamos lector_id aquí, el sistema lo asignará solo
        ]);

        $libro = Libro::find($request->libro_id);
        if ($libro->stock <= 0) {
            return back()->withErrors(['error' => 'No hay ejemplares disponibles.']);
        }

        // --- LÓGICA SENIOR PARA OBTENER EL LECTOR ---
        $lector_id = null;

        if (auth()->user()->role == 'admin') {
            // Si es admin, debe enviar el lector_id desde el formulario
            $request->validate(['lector_id' => 'required|exists:lectors,id']);
            $lector_id = $request->lector_id;
        } else {
            // Si es lector, lo buscamos por su correo
            $lector = \App\Models\Lector::where('email', auth()->user()->email)->first();
            if (!$lector) {
                return back()->withErrors(['error' => 'Tu cuenta no está vinculada a un perfil de lector. Pide al bibliotecario que te registre en el sistema.']);
            }
            $lector_id = $lector->id;
        }

        // Si es admin, el préstamo es directo. Si es lector, es una solicitud.
        $estadoInicial = (auth()->user()->role == 'admin') ? 'Prestado' : 'Solicitado';
        $fechaLimite = date('Y-m-d', strtotime($request->fecha_prestamo . ' + 7 days'));

        Prestamo::create([
            'libro_id' => $request->libro_id,
            'lector_id' => $lector_id,
            'fecha_prestamo' => $request->fecha_prestamo,
            'fecha_limite' => $fechaLimite,
            'estado' => $estadoInicial
        ]);

        // Solo descontamos el stock si el admin lo autoriza de inmediato
        if ($estadoInicial == 'Prestado') {
            $libro->decrement('stock');
            return redirect()->route('prestamos.index')->with('success', 'Préstamo registrado exitosamente.');
        }

        return redirect()->route('libros.index')->with('success', 'Solicitud enviada. El bibliotecario la revisará pronto.');
    }

    public function edit(Prestamo $prestamo)
    {
        return view('prestamos.edit', compact('prestamo'));
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        if ($request->estado == 'Devuelto' && $prestamo->estado == 'Prestado') {
            $fechaDevolucionReal = date('Y-m-d');
            $multa = 0;

            // ✅ SOLUCIÓN: Verificamos que la fecha_limite no sea nula antes de calcular
            if ($prestamo->fecha_limite != null && $fechaDevolucionReal > $prestamo->fecha_limite) {
                $fechaLimiteObj = new \DateTime($prestamo->fecha_limite);
                $fechaDevolucionObj = new \DateTime($fechaDevolucionReal);
                $diferencia = $fechaLimiteObj->diff($fechaDevolucionObj);
                $diasRetraso = $diferencia->days;

                // Cobrar $10 por cada día de retraso
                $multa = $diasRetraso * 10; 
            }

            $prestamo->update([
                'estado' => 'Devuelto',
                'fecha_devolucion' => $fechaDevolucionReal,
                'multa' => $multa
            ]);

            $prestamo->libro->increment('stock');

            if ($multa > 0) {
                return redirect()->route('prestamos.index')->with('success', "Libro devuelto. ¡Se generó una multa de $$multa por retraso!");
            }
        }

        return redirect()->route('prestamos.index')->with('success', 'Libro devuelto a tiempo. Sin multas.');
    }

    public function destroy(Prestamo $prestamo)
    {
        // Si se elimina el registro y el libro no se ha devuelto, devolvemos el stock
        if ($prestamo->estado == 'Prestado') {
            $prestamo->libro->increment('stock');
        }
        
        $prestamo->delete();
        return redirect()->route('prestamos.index')->with('success', 'Préstamo eliminado.');
    }

    public function renovar(Prestamo $prestamo)
    {
        if ($prestamo->estado == 'Prestado') {
            // Sumamos 7 días a la fecha límite actual
            $nuevaFechaLimite = date('Y-m-d', strtotime($prestamo->fecha_limite . ' + 7 days'));
            
            $prestamo->update([
                'fecha_limite' => $nuevaFechaLimite
            ]);

            return redirect()->route('prestamos.index')->with('success', 'Préstamo renovado. Nueva fecha límite: ' . $nuevaFechaLimite);
        }

        return redirect()->route('prestamos.index')->withErrors(['error' => 'Solo se pueden renovar préstamos activos.']);
    }

    public function aprobar(Prestamo $prestamo)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        if ($prestamo->estado == 'Solicitado') {
            if ($prestamo->libro->stock > 0) {
                // ✅ Se aprueba, pero AÚN NO se descuenta el stock.
                // Se da un plazo de 2 días para recogerlo.
                $fechaRecoger = date('Y-m-d', strtotime('+2 days'));
                
                $prestamo->update([
                    'estado' => 'Aprobado',
                    'fecha_recoger' => $fechaRecoger
                ]);
                
                return back()->with('success', 'Solicitud aprobada. El lector tiene hasta el ' . $fechaRecoger . ' para recogerlo.');
            }
        }
        return back();
    }

    // El admin rechaza la solicitud
    public function rechazar(Prestamo $prestamo)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        if ($prestamo->estado == 'Solicitado') {
            $prestamo->update(['estado' => 'Rechazado']);
            return back()->with('success', 'Solicitud rechazada.');
        }
        return back();
    }

    public function entregar(Prestamo $prestamo)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        if ($prestamo->estado == 'Aprobado') {
            // ✅ Aquí es donde el lector llega físicamente. Inicia el préstamo.
            $prestamo->update([
                'estado' => 'Prestado',
                'fecha_prestamo' => date('Y-m-d'),
                'fecha_limite' => date('Y-m-d', strtotime('+7 days'))
            ]);
            
            // Ahora sí, descontamos el stock
            $prestamo->libro->decrement('stock');
            
            return back()->with('success', 'Libro entregado. El préstamo ha comenzado.');
        }
        return back();
    }
    public function generarComprobante(Prestamo $prestamo)
    {
        // Solo el admin o el dueño del préstamo pueden imprimirlo
        if (auth()->user()->role !== 'admin' && $prestamo->lector->email !== auth()->user()->email) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.comprobante', compact('prestamo'));
        return $pdf->download('Comprobante_Prestamo_' . $prestamo->id . '.pdf');
    }

        public function reportePdf()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $prestamos = Prestamo::where('estado', 'Prestado')->with(['libro', 'lector'])->get();
        
        $pdf = Pdf::loadView('pdf.reporte-prestamos', compact('prestamos'));
        // Opcional: $pdf->setPaper('a4', 'landscape'); // Horizontal para que quepan más columnas
        return $pdf->download('Reporte_Prestamos_' . date('Y-m-d') . '.pdf');
    }

    public function reporteVencidos()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $hoy = date('Y-m-d');
        // Buscar solo los préstamos que no se han devuelto y su fecha límite ya pasó
        $vencidos = Prestamo::where('estado', 'Prestado')
            ->where('fecha_limite', '<', $hoy)
            ->with(['libro', 'lector'])
            ->get();

        $pdf = Pdf::loadView('pdf.reporte-vencidos', compact('vencidos', 'hoy'));
        return $pdf->download('Reporte_Morosos_' . $hoy . '.pdf');
    }
}