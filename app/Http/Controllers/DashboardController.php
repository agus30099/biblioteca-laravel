<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Lector;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('reservas.index')->with('success', 'Bienvenido a tu panel de lector.');
        }

        $totalLibros = Libro::sum('stock');
        $totalLectores = Lector::count();
        $prestamosActivos = Prestamo::where('estado', 'Prestado')->count();
        $ultimosPrestamos = Prestamo::with(['libro', 'lector'])->latest()->take(5)->get();

        // ✅ NUEVO: Datos para el gráfico
        $librosMasPrestados = Prestamo::select('libro_id', DB::raw('count(*) as total'))
            ->groupBy('libro_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('libro')
            ->get();

        $titulosGrafico = $librosMasPrestados->pluck('libro.titulo');
        $cantidadesGrafico = $librosMasPrestados->pluck('total');

        return view('dashboard', compact('totalLibros', 'totalLectores', 'prestamosActivos', 'ultimosPrestamos', 'titulosGrafico', 'cantidadesGrafico'));
    }
}