@extends('layouts.app')

@section('title', 'Libros')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catálogo de Libros</h2>
        <a href="{{ route('libros.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Libro
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($libros as $libro)
                        <tr>
                            <td>{{ $libro->id }}</td>
                            <td>{{ $libro->titulo }}</td>
                            <td>{{ $libro->autor }}</td>
                            <td>{{ $libro->isbn }}</td>
                            <td>
                                <span class="badge bg-{{ $libro->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $libro->stock }}
                                </span>
                            </td>
                            <!-- Aquí demostramos la relación de Eloquent -->
                            <td>{{ $libro->categoria->nombre }}</td>
                            <td>
                                <a href="{{ route('libros.edit', $libro) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('libros.destroy', $libro) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar libro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay libros registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection