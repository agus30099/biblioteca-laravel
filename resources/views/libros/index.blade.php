@extends('layouts.app')

@section('title', 'Libros')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catálogo de Libros</h2>
        <div>
            @auth
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('libros.export') }}" class="btn btn-outline-success me-2">
                        <i class="bi bi-file-earmark-excel"></i> Exportar CSV
                    </a>
                    <a href="{{ route('libros.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Nuevo Libro
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Barra de Búsqueda (Todos pueden buscar) -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('libros.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por título..." value="{{ $buscar ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
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
                            <td>{{ $libro->autor->nombre ?? 'Sin autor' }}</td>
                            <td>{{ $libro->isbn }}</td>
                            <td>
                                <span class="badge bg-{{ $libro->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $libro->stock }}
                                </span>
                            </td>
                            <td>{{ $libro->categoria->nombre }}</td>
                            <td>
                                @auth
                                    @if(auth()->user()->role == 'admin')
                                        <!-- Botones de Admin -->
                                        <a href="{{ route('libros.edit', $libro) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('libros.destroy', $libro) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar libro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    @else
                                        <!-- Botones de Lector -->
                                        @if($libro->stock == 0)
                                            <form action="{{ route('reservas.store') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                                                <button type="submit" class="btn btn-info btn-sm text-white" onclick="return confirm('¿Reservar este libro?')">
                                                    <i class="bi bi-bookmark-plus"></i> Reservar
                                                </button>
                                            </form>
                                        @else
                                            <!-- Si hay stock, el lector puede solicitar el préstamo (Sin lector_id por seguridad) -->
                                            <form action="{{ route('prestamos.store') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                                                <input type="hidden" name="fecha_prestamo" value="{{ date('Y-m-d') }}">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Solicitar este libro al bibliotecario?')">
                                                    <i class="bi bi-hand-index"></i> Solicitar Préstamo
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <!-- Si es un invitado (No logueado) -->
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-box-arrow-in-right"></i> Inicia sesión
                                    </a>
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay libros registrados o no coinciden con la búsqueda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- ✅ Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $libros->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection