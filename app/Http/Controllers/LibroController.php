<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Categoria;

class LibroController extends Controller
{
    //
    public function index()
    {
        $libros = Libro::with('categoria')->latest()->get();
        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        // Enviamos las categorías al formulario para el <select>
        $categorias = Categoria::all();
        return view('libros.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|unique:libros,isbn',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        Libro::create($request->all());

        return redirect()->route('libros.index')->with('success', 'Libro registrado exitosamente.');
    }

    public function edit(Libro $libro)
    {
        $categorias = Categoria::all();
        return view('libros.edit', compact('libro', 'categorias'));
    }

    public function update(Request $request, Libro $libro)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|unique:libros,isbn,' . $libro->id, // Ignorar el ISBN del libro actual
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        $libro->update($request->all());

        return redirect()->route('libros.index')->with('success', 'Libro actualizado.');
    }

    public function destroy(Libro $libro)
    {
        $libro->delete();
        return redirect()->route('libros.index')->with('success', 'Libro eliminado.');
    }
}
