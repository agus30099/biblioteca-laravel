<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LectorController;
use App\Http\Controllers\AuthController;

// --- RUTAS PÚBLICAS ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- RUTAS PROTEGIDAS (Solo si hay sesión) ---
Route::middleware('auth')->group(function () {
    
    // Ruta principal
    Route::get('/', function () {
        return redirect()->route('categorias.index');
    });

    // Rutas Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Rutas Libros
    Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
    Route::get('/libros/crear', [LibroController::class, 'create'])->name('libros.create');
    Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
    Route::get('/libros/{libro}/editar', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{libro}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{libro}', [LibroController::class, 'destroy'])->name('libros.destroy');

    // Rutas Lectores
    Route::get('/lectores', [LectorController::class, 'index'])->name('lectores.index');
    Route::get('/lectores/crear', [LectorController::class, 'create'])->name('lectores.create');
    Route::post('/lectores', [LectorController::class, 'store'])->name('lectores.store');
    Route::get('/lectores/{lectore}/editar', [LectorController::class, 'edit'])->name('lectores.edit');
    Route::put('/lectores/{lectore}', [LectorController::class, 'update'])->name('lectores.update');
    Route::delete('/lectores/{lectore}', [LectorController::class, 'destroy'])->name('lectores.destroy');
});