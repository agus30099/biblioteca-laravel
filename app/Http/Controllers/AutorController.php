<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index()
    {
        $autores = Autor::latest()->get();
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nacionalidad' => 'nullable|string|max:100',
        ]);

        Autor::create($request->all());

        return redirect()->route('autores.index')->with('success', 'Autor registrado exitosamente.');
    }

    public function edit(Autor $autore) // Laravel pluraliza Autor a Autore
    {
        return view('autores.edit', ['autor' => $autore]);
    }

    public function update(Request $request, Autor $autore)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nacionalidad' => 'nullable|string|max:100',
        ]);

        $autore->update($request->all());

        return redirect()->route('autores.index')->with('success', 'Autor actualizado correctamente.');
    }

    public function destroy(Autor $autore)
    {
        // Validar que no tenga libros asociados antes de borrar (Buena práctica Senior)
        if ($autore->libros()->count() > 0) {
            return redirect()->route('autores.index')->withErrors(['error' => 'No se puede eliminar el autor porque tiene libros registrados.']);
        }

        $autore->delete();
        return redirect()->route('autores.index')->with('success', 'Autor eliminado.');
    }
}