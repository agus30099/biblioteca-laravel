@extends('layouts.app')

@section('title', 'Lectores')

@section('content')

<style>
    .readers-page { color: #493729; }
    .readers-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 28px; }
    .readers-header h2 { color: #0D4032; font-weight: 800; margin: 0; }
    .readers-header p { color: #766656; margin: 5px 0 0; }
    .btn-new-reader { background: #145A45; color: #FFFFFF; border: 1px solid #D4A64A; padding: 11px 20px; border-radius: 10px; font-weight: 600; transition: .2s; }
    .btn-new-reader:hover { background: #0D4032; color: #FFFFFF; transform: translateY(-2px); }
    
    .readers-card { background: #FFFFFF; border: 1px solid #e5dccd; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(73,55,41,.08); }
    .readers-card-header { background: #0D4032; color: #FFFFFF; padding: 19px 22px; border-bottom: 3px solid #D4A64A; }
    .readers-card-body { padding: 22px; background: #FFFFFF; }
    
    .readers-table thead th { background: #F5F0E6; color: #493729; padding: 16px; border-bottom: 2px solid #D4A64A; font-size: 13px; text-transform: uppercase; white-space: nowrap; }
    .readers-table tbody td { padding: 17px 16px; vertical-align: middle; color: #493729; border-color: #e9e0d3; }
    
    .reader-name { font-weight: 700; color: #0D4032; }
    .btn-edit-reader { background: #f8edcf; color: #76551d; border: 1px solid #D4A64A; border-radius: 8px; padding: 7px 12px; }
    .btn-delete-reader { background: #f7e5df; color: #963f2d; border: 1px solid #dfb6a9; border-radius: 8px; padding: 7px 12px; }
    .btn-pay-fine { background: #145A45; color: white; border: 1px solid #145A45; border-radius: 8px; padding: 7px 12px; }
    .btn-pay-fine:hover { background: #0D4032; color: white; }
    
    .search-input { border-radius: 10px; padding: 11px 14px; border-color: #D8CDBB; }
    .search-input:focus { border-color: #145A45; box-shadow: 0 0 0 .2rem rgba(20, 90, 69, .15); }
</style>

<div class="readers-page">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="readers-header">
        <div>
            <h2><i class="bi bi-people me-2"></i>Gestión de Lectores</h2>
            <p>Administra la información y los adeudos de los alumnos.</p>
        </div>

        @if(auth()->user()->isStaff())
            <a href="{{ route('lectores.create') }}" class="btn btn-new-reader">
                <i class="bi bi-person-plus me-2"></i> Nuevo Lector
            </a>
        @endif
    </div>

    <!-- Buscador -->
    <div class="card readers-card mb-4">
        <div class="card-body">
            <form action="{{ route('lectores.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="buscar" class="form-control search-input" placeholder="Buscar por nombre, apellido o correo..." value="{{ $buscar ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-new-reader w-100"><i class="bi bi-search me-1"></i> Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="readers-card">
        <div class="readers-card-header">
            <h5><i class="bi bi-person-vcard me-2"></i> Directorio de Lectores</h5>
        </div>

        <div class="readers-card-body">
            <div class="table-responsive">
                <table class="table readers-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Contacto</th>
                            <th>Estado de Cuenta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lectores as $lector)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $lector->id }}</span></td>
                                <td>
                                    <span class="reader-name">
                                        <i class="bi bi-person-circle me-2"></i>
                                        {{ $lector->nombre }} {{ $lector->apellido }}
                                    </span>
                                </td>
                                <td>
                                    <small><i class="bi bi-envelope me-1"></i> {{ $lector->email }}</small><br>
                                    <small><i class="bi bi-telephone me-1"></i> {{ $lector->telefono ?: 'Sin teléfono' }}</small>
                                </td>
                                <td>
                                    @if($lector->tieneAdeudo())
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i> Con Adeudo</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Al corriente</span>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->isStaff())
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if($lector->tieneAdeudo())
                                                <form action="{{ route('lectores.pagarMulta', $lector) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirmar que el lector pagó su adeudo?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-pay-fine btn-sm">
                                                        <i class="bi bi-cash-coin"></i> Saldar Multa
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('lectores.edit', $lector) }}" class="btn btn-edit-reader btn-sm">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                            
                                            <a href="{{ route('lectores.credencial', $lector) }}" class="btn btn-edit-reader btn-sm">
                                                <i class="bi bi-person-badge"></i> Credencial
                                            </a>

                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('lectores.destroy', $lector) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar lector?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-delete-reader btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Solo lectura</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No se encontraron lectores.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $lectores->appends(['buscar' => $buscar])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection