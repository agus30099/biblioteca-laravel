<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Lector;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        // Si es admin, ve todas las reservas. Si es lector, solo las suyas.
        if (auth()->user()->role == 'admin') {
            $reservas = Reserva::with(['libro', 'lector'])->latest()->get();
        } else {
            $reservas = Reserva::with(['libro', 'lector'])
                ->whereHas('lector', function($query) {
                    $query->where('email', auth()->user()->email);
                })->latest()->get();
        }
        return view('reservas.index', compact('reservas'));
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