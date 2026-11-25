
@extends('layouts.app')

@section('title', 'Catálogo de Libros')

@section('content')

<style>
    .catalog-header {
        background: linear-gradient(135deg, #0D4032, #145A45);
        color: white;
        border-left: 5px solid #D4A64A;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 25px;
    }

    .catalog-header h2 {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .catalog-header p {
        color: #E5E0D5;
        margin-bottom: 0;
    }

    .catalog-card {
        background: #FFFFFF;
        border: 1px solid #E8E0D2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(73, 55, 41, .08);
        overflow: hidden;
    }

    .catalog-card .card-body {
        padding: 22px;
    }

    .catalog-table thead th {
        background: #145A45;
        color: white;
        font-size: 13px;
        font-weight: 600;
        padding: 15px;
        white-space: nowrap;
    }

    .catalog-table tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #493729;
    }

    .catalog-table tbody tr:hover {
        background: #F5F0E6;
    }

    .book-title {
        font-weight: 700;
        color: #0D4032;
    }

    .book-cover {
        width: 65px;
        height: 90px;
        object-fit: cover;
        border-radius: 7px;
        box-shadow: 0 3px 8px rgba(0,0,0,.18);
        border: 1px solid #E8E0D2;
        background: #F5F0E6;
    }

    .book-cover-placeholder {
        width: 65px;
        height: 90px;
        background: #F5F0E6;
        color: #145A45;
        border-radius: 7px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        border: 1px solid #E8E0D2;
    }

    .stock-badge {
        padding: 7px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .search-input {
        border-radius: 10px;
        padding: 11px 14px;
        border-color: #D8CDBB;
    }

    .search-input:focus {
        border-color: #145A45;
        box-shadow: 0 0 0 .2rem rgba(20, 90, 69, .15);
    }

    .catalog-btn {
        border-radius: 9px;
        padding: 10px 16px;
        font-weight: 600;
    }

    .catalog-actions {
        min-width: 190px;
    }

    .catalog-actions .btn {
        margin: 2px;
        border-radius: 8px;
    }

    .btn-catalog {
        background: #145A45;
        border-color: #145A45;
        color: white;
    }

    .btn-catalog:hover {
        background: #0D4032;
        border-color: #0D4032;
        color: white;
    }

    .btn-gold {
        background: #D4A64A;
        border-color: #D4A64A;
        color: #493729;
    }

    .btn-gold:hover {
        background: #C29335;
        border-color: #C29335;
        color: #493729;
    }

    .btn-outline-gold {
        border-color: #D4A64A;
        color: #493729;
    }

    .btn-outline-gold:hover {
        background: #D4A64A;
        color: #493729;
    }

    @media (max-width: 768px) {
        .catalog-header {
            padding: 20px;
        }

        .catalog-card .card-body {
            padding: 12px;
        }
    }
</style>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!--Mensaje de Éxito -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="catalog-header d-flex justify-content-between align-items-center flex-wrap gap-3">

    <div>
        <h2>
            <i class="bi bi-book-half me-2"></i>
            Catálogo de Libros
        </h2>

        <p>Explora y administra los libros disponibles en la biblioteca.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">

        @auth
            @if(auth()->user()->isStaff())

                <!-- ✅ CORREGIDO: Solo un botón de exportar, y envía la búsqueda actual -->
                <a href="{{ route('libros.export', ['buscar' => $buscar]) }}"
                   class="btn btn-outline-light catalog-btn">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Exportar CSV
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('libros.create') }}"
                       class="btn btn-gold catalog-btn">
                        <i class="bi bi-plus-circle me-1"></i>
                        Nuevo Libro
                    </a>
                @endif

            @endif
        @endauth

    </div>
</div>

<div class="card catalog-card mb-4">
    <div class="card-body">

        <form action="{{ route('libros.index') }}" method="GET" class="row g-2">

            <div class="col-md-10">
                <input
                    type="text"
                    name="buscar"
                    class="form-control search-input"
                    placeholder="Buscar libro por título..."
                    value="{{ $buscar ?? '' }}"
                >
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-catalog catalog-btn w-100">
                    <i class="bi bi-search me-1"></i>
                    Buscar
                </button>
            </div>

        </form>

    </div>
</div>

<div class="card catalog-card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="fw-bold mb-0" style="color:#0D4032;">
                <i class="bi bi-collection me-2"></i>
                Libros registrados
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover catalog-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Portada</th>
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

                            <td class="text-muted">
                                #{{ $libro->id }}
                            </td>

                            <td>
                                <img
                                    src="https://covers.openlibrary.org/b/isbn/{{ str_replace('-', '', $libro->isbn) }}-M.jpg"
                                    class="book-cover"
                                    alt="Portada de {{ $libro->titulo }}"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >

                                <div class="book-cover-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>
                            </td>

                            <td>
                                <span class="book-title">{{ $libro->titulo }}</span>
                                <br>
                                <!-- ✅ NUEVO: Mostrar estrellas -->
                                @php $promedio = round($libro->promedioCalificacion(), 1); @endphp
                                <div class="text-warning small mt-1" title="{{ $promedio }} de 5 estrellas">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $promedio)
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $promedio)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted ms-1">({{ $libro->resenas->count() }})</span>
                                </div>
                            </td>

                            <td>
                                {{ $libro->autor->nombre ?? 'Sin autor' }}
                            </td>

                            <td>
                                <small>{{ $libro->isbn }}</small>
                            </td>

                            <td>
                                <span class="badge stock-badge bg-{{ $libro->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $libro->stock > 0 ? $libro->stock . ' disponibles' : 'Agotado' }}
                                </span>
                            </td>

                            <td>
                                {{ $libro->categoria->nombre ?? 'Sin categoría' }}
                            </td>

                            <td class="catalog-actions">

                                @auth

                                    @if(auth()->user()->isStaff())

                                        <!-- Botones de Staff (Admin/Bibliotecario) -->
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('libros.edit', $libro) }}"
                                               class="btn btn-sm btn-outline-gold"
                                               title="Editar libro">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>

                                            <form action="{{ route('libros.destroy', $libro) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar libro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        @endif

                                    @else

                                         <!-- Botones de Lector -->
                                        @if(in_array($libro->id, $prestamosUsuario))
                                            <!-- Si ya lo tiene solicitado o prestado -->
                                            <span class="badge bg-primary py-2 px-3">
                                                <i class="bi bi-hourglass-split me-1"></i> En proceso
                                            </span>
                                        @elseif(in_array($libro->id, $reservasUsuario))
                                            <!-- Si ya lo tiene reservado -->
                                            <span class="badge bg-info text-dark py-2 px-3">
                                                <i class="bi bi-bookmark-check me-1"></i> Reservado
                                            </span>
                                        @else
                                            <!-- Si no lo tiene, mostrar los botones normales -->
                                            @if($libro->stock == 0)
                                                <form action="{{ route('reservas.store') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                                                    <button type="submit" class="btn btn-sm btn-gold">
                                                        <i class="bi bi-bookmark-plus"></i> Reservar
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('prestamos.store') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                                                    <input type="hidden" name="fecha_prestamo" value="{{ date('Y-m-d') }}">
                                                    <button type="submit" class="btn btn-sm btn-catalog">
                                                        <i class="bi bi-hand-index"></i> Solicitar
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        <!-- Botón de Calificar -->
                                        <button type="button" class="btn btn-sm btn-outline-warning mt-1" data-bs-toggle="modal" data-bs-target="#modalResena{{ $libro->id }}" title="Calificar libro">
                                            <i class="bi bi-star"></i> Calificar
                                        </button>

                                        <!-- Modal de Calificación -->
                                        <div class="modal fade" id="modalResena{{ $libro->id }}" tabindex="-1">
                                            <div class="modal-dialog text-start">
                                                <div class="modal-content">
                                                    <form action="{{ route('resenas.store', $libro) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title">Calificar: {{ $libro->titulo }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label text-dark">Calificación:</label>
                                                                <select name="calificacion" class="form-select" required>
                                                                    <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                                                    <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                                                                    <option value="3">⭐⭐⭐ Bueno</option>
                                                                    <option value="2">⭐⭐ Regular</option>
                                                                    <option value="1">⭐ Malo</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label text-dark">Comentario (opcional):</label>
                                                                <textarea name="comentario" class="form-control" rows="3" placeholder="¿Qué te pareció el libro?"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-warning">Enviar Calificación</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @endif

                                @else

                                    <!-- Si es invitado (No logueado) -->
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-gold">
                                        <i class="bi bi-box-arrow-in-right"></i> Inicia sesión
                                    </a>

                                @endauth

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">

                                <i class="bi bi-book fs-1 d-block mb-2"></i>

                                No hay libros registrados o no coinciden con la búsqueda.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-center mt-4">

            {{ $libros->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}

        </div>

    </div>
</div>

@endsection
