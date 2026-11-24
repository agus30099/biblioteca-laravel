<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LectorController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// ==========================================
// RUTAS PÚBLICAS (Sin necesidad de iniciar sesión)
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('libros.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// El catálogo es público
Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');


// ==========================================
// RUTAS PROTEGIDAS (Requieren login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');  
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Libros ---
    Route::get('/libros/exportar', [LibroController::class, 'exportCsv'])->name('libros.export');
    Route::get('/libros/crear', [LibroController::class, 'create'])->name('libros.create');
    Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
    Route::get('/libros/{libro}/editar', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{libro}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{libro}', [LibroController::class, 'destroy'])->name('libros.destroy');

    // --- Categorías ---
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // --- Autores ---
    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/autores/crear', [AutorController::class, 'create'])->name('autores.create');
    Route::post('/autores', [AutorController::class, 'store'])->name('autores.store');
    Route::get('/autores/{autore}/editar', [AutorController::class, 'edit'])->name('autores.edit');
    Route::put('/autores/{autore}', [AutorController::class, 'update'])->name('autores.update');
    Route::delete('/autores/{autore}', [AutorController::class, 'destroy'])->name('autores.destroy');

    // --- Lectores ---
    Route::get('/lectores', [LectorController::class, 'index'])->name('lectores.index');
    Route::get('/lectores/crear', [LectorController::class, 'create'])->name('lectores.create');
    Route::post('/lectores', [LectorController::class, 'store'])->name('lectores.store');
    Route::get('/lectores/{lectore}/editar', [LectorController::class, 'edit'])->name('lectores.edit');
    Route::put('/lectores/{lectore}', [LectorController::class, 'update'])->name('lectores.update');
    Route::delete('/lectores/{lectore}', [LectorController::class, 'destroy'])->name('lectores.destroy');
    Route::get('/lectores/{lectore}/credencial', [LectorController::class, 'credencial'])->name('lectores.credencial');
    Route::post('/lectores/{lectore}/pagar-multa', [App\Http\Controllers\LectorController::class, 'pagarMulta'])->name('lectores.pagarMulta');

    // --- Préstamos ---
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::get('/prestamos/{prestamo}/editar', [PrestamoController::class, 'edit'])->name('prestamos.edit');
    Route::put('/prestamos/{prestamo}', [PrestamoController::class, 'update'])->name('prestamos.update');
    Route::delete('/prestamos/{prestamo}', [PrestamoController::class, 'destroy'])->name('prestamos.destroy');
    Route::put('/prestamos/{prestamo}/renovar', [PrestamoController::class, 'renovar'])->name('prestamos.renovar');
    Route::post('/prestamos/{prestamo}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::post('/prestamos/{prestamo}/rechazar', [PrestamoController::class, 'rechazar'])->name('prestamos.rechazar');
    Route::get('/prestamos/{prestamo}/comprobante', [PrestamoController::class, 'generarComprobante'])->name('prestamos.comprobante');
    Route::get('/prestamos/reporte/pdf', [PrestamoController::class, 'reportePdf'])->name('prestamos.reporte');
    Route::get('/prestamos/reporte/vencidos', [PrestamoController::class, 'reporteVencidos'])->name('prestamos.reporte.vencidos');
    
    // ✅ AQUÍ ESTABA EL ERROR: Faltaba el punto y coma al final
    Route::post('/prestamos/{prestamo}/entregar', [PrestamoController::class, 'entregar'])->name('prestamos.entregar');

    // --- Reservas ---
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
});