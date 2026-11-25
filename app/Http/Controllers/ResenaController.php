<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Libro;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function store(Request $request, Libro $libro)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500'
        ]);

        // Verificar si el usuario ya calificó este libro
        $existe = Resena::where('user_id', auth()->id())->where('libro_id', $libro->id)->exists();
        
        if ($existe) {
            return back()->withErrors(['error' => 'Ya has calificado este libro anteriormente.']);
        }

        Resena::create([
            'user_id' => auth()->id(),
            'libro_id' => $libro->id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario
        ]);

        return back()->with('success', '¡Gracias por tu calificación!');
    }
}