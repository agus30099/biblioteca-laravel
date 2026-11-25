<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Categoria;
use App\Models\Autor;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        
        $libros = Libro::with(['categoria', 'autor', 'resenas'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('titulo', 'like', "%$buscar%");
            })->latest()->paginate(10);

        // ✅ NUEVO: Obtener los libros que el lector ya tiene solicitados o reservados
        $prestamosUsuario = [];
        $reservasUsuario = [];

        if (auth()->check() && auth()->user()->isLector()) {
            $lector = \App\Models\Lector::where('email', auth()->user()->email)->first();
            if ($lector) {
                // Libros con préstamo activo (Solicitado, Aprobado o Prestado)
                $prestamosUsuario = \App\Models\Prestamo::where('lector_id', $lector->id)
                    ->whereIn('estado', ['Solicitado', 'Aprobado', 'Prestado'])
                    ->pluck('libro_id')->toArray();

                // Libros con reserva pendiente
                $reservasUsuario = \App\Models\Reserva::where('lector_id', $lector->id)
                    ->where('estado', 'Pendiente')
                    ->pluck('libro_id')->toArray();
            }
        }

        return view('libros.index', compact('libros', 'buscar', 'prestamosUsuario', 'reservasUsuario'));
    }

    public function create()
    {
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $categorias = Categoria::all();
        $autores = Autor::all(); 
        return view('libros.create', compact('categorias', 'autores'));
    }

    public function store(Request $request)
    {
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autors,id',
            'isbn' => 'required|string|unique:libros,isbn',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        Libro::create($request->all());
        return redirect()->route('libros.index')->with('success', 'Libro registrado.');
    }

    public function edit(Libro $libro)
    {
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $categorias = Categoria::all();
        $autores = Autor::all(); 
        return view('libros.edit', compact('libro', 'categorias', 'autores'));
    }

    public function update(Request $request, Libro $libro)
    {
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autors,id',
            'isbn' => 'required|string|unique:libros,isbn,' . $libro->id,
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        $libro->update($request->all());
        return redirect()->route('libros.index')->with('success', 'Libro actualizado.');
    }

    public function destroy(Libro $libro)
    {
        // ✅ SOLO el admin puede eliminar libros
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo el administrador puede eliminar libros.');
        }

        $libro->delete();
        return redirect()->route('libros.index')->with('success', 'Libro eliminado.');
    }

    public function exportCsv(Request $request)
    {
        $buscar = $request->get('buscar');

        // Exportar solo lo que se está buscando (si hay filtro)
        $libros = Libro::with(['categoria', 'autor'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('titulo', 'like', "%$buscar%")
                             ->orWhere('isbn', 'like', "%$buscar%");
            })->get();

        $filename = "Catalogo_Libros_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($libros) {
            $file = fopen('php://output', 'w');

            // ✅ 1. MAGIA SENIOR: Agregar BOM UTF-8 para que Excel lea acentos y eñes
            fwrite($file, "\xEF\xBB\xBF");

            // ✅ 2. Usar punto y coma (;) en lugar de coma para Excel en español
            fputcsv($file, ['ID', 'Título', 'Autor', 'ISBN', 'Stock', 'Categoría'], ';');

            foreach ($libros as $libro) {
                fputcsv($file, [
                    $libro->id,
                    $libro->titulo,
                    $libro->autor->nombre ?? 'Sin autor',
                    $libro->isbn,
                    $libro->stock,
                    $libro->categoria->nombre
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}