<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Lector;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->get('estado'); // Capturamos el filtro de la URL

        // Iniciamos la consulta base
        $query = Prestamo::with(['libro', 'lector']);

        // Si es lector, solo ve sus propios préstamos
        if (auth()->user()->role == 'lector' || auth()->user()->role == 'usuario') {
            $query->whereHas('lector', function($q) {
                $q->where('email', auth()->user()->email);
            });
        }

        // Aplicar el filtro si se seleccionó uno
        if ($estado) {
            $query->where('estado', $estado);
        } else {
            // Si no hay filtro, ordenamos para que lo más prioritario salga primero
            // (Solicitado y Aprobado primero, luego Prestado, luego Devuelto)
            $query->orderByRaw("FIELD(estado, 'Solicitado', 'Aprobado', 'Prestado', 'Rechazado', 'Devuelto')");
        }

        $prestamos = $query->latest()->paginate(10);

        return view('prestamos.index', compact('prestamos', 'estado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
        ]);

        // Buscar al lector por el correo del usuario logueado
        $lector = Lector::where('email', auth()->user()->email)->first();

        if (!$lector) {
            return back()->withErrors(['error' => 'Tu cuenta no está vinculada a un perfil de lector. Pide al bibliotecario que te registre.']);
        }

        // Evitar que reserve dos veces el mismo libro
        $existe = Reserva::where('libro_id', $request->libro_id)
            ->where('lector_id', $lector->id)
            ->where('estado', 'Pendiente')
            ->exists();

        if ($existe) {
            return back()->withErrors(['error' => 'Ya tienes una reserva pendiente para este libro.']);
        }

        Reserva::create([
            'libro_id' => $request->libro_id,
            'lector_id' => $lector->id,
            'fecha_reserva' => date('Y-m-d'),
            'estado' => 'Pendiente'
        ]);

        return back()->with('success', '¡Libro reservado! Te avisaremos cuando esté disponible.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada.');
    }
}