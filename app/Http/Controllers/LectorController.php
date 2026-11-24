<?php

namespace App\Http\Controllers;

use App\Models\Lector;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LectorController extends Controller
{
    public function index()
    {
        $lectores = Lector::latest()->get();
        return view('lectores.index', compact('lectores'));
    }

    public function create()
    {
        return view('lectores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            // ✅ CORREGIDO: Ahora dice 'lectors'
            'email' => 'required|email|unique:lectors,email', 
            'telefono' => 'nullable|string|max:20'
        ]);

        Lector::create($request->all());

        return redirect()->route('lectores.index')->with('success', 'Lector registrado exitosamente.');
    }

    public function edit(Lector $lectore) // Laravel usa 'lectore' por la pluralización
    {
        return view('lectores.edit', ['lector' => $lectore]);
    }

    public function update(Request $request, Lector $lectore)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            // ✅ CORREGIDO: Ahora dice 'lectors'
            'email' => 'required|email|unique:lectors,email,' . $lectore->id,
            'telefono' => 'nullable|string|max:20'
        ]);

        $lectore->update($request->all());

        return redirect()->route('lectores.index')->with('success', 'Lector actualizado.');
    }

    public function destroy(Lector $lectore)
    {
        $lectore->delete();
        return redirect()->route('lectores.index')->with('success', 'Lector eliminado.');
    }

        public function credencial(Lector $lectore)
    {
        // Solo el admin o el propio lector pueden ver su credencial
        if (auth()->user()->role !== 'admin' && $lectore->email !== auth()->user()->email) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.credencial', ['lector' => $lectore]);
        // Formato de tarjeta (ancho 8.5cm, alto 5.4cm)
        $pdf->setPaper([0, 0, 241.89, 153.07], 'landscape'); 
        
        return $pdf->download('Credencial_' . $lectore->id . '.pdf');
    }
}