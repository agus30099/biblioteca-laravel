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
        
        $libros = Libro::with(['categoria', 'autor'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('titulo', 'like', "%$buscar%");
            })->latest()->paginate(10); // <-- Cambiado a paginate(10)
            
        return view('libros.index', compact('libros', 'buscar'));
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
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $libro->delete();
        return redirect()->route('libros.index')->with('success', 'Libro eliminado.');
    }

    public function exportCsv()
    {
        // Seguridad: Solo admin
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');

        $libros = Libro::with(['categoria', 'autor'])->get();
        $filename = "libros_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($libros) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Título', 'Autor', 'ISBN', 'Stock', 'Categoría']);

            foreach ($libros as $libro) {
                fputcsv($file, [
                    $libro->id,
                    $libro->titulo,
                    $libro->autor->nombre ?? 'Sin autor',
                    $libro->isbn,
                    $libro->stock,
                    $libro->categoria->nombre
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}