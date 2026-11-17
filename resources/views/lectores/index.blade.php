
@extends('layouts.app')

@section('title', 'Lectores')

@section('content')

<style>
    .readers-page {
        color: #493729;
    }

    .readers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 28px;
    }

    .readers-header h2 {
        color: #0D4032;
        font-weight: 800;
        margin: 0;
    }

    .readers-header p {
        color: #766656;
        margin: 5px 0 0;
    }

    .btn-new-reader {
        background: #145A45;
        color: #FFFFFF;
        border: 1px solid #D4A64A;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-new-reader:hover {
        background: #0D4032;
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    .readers-card {
        background: #FFFFFF;
        border: 1px solid #e5dccd;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(73,55,41,.08);
    }

    .readers-card-header {
        background: #0D4032;
        color: #FFFFFF;
        padding: 19px 22px;
        border-bottom: 3px solid #D4A64A;
    }

    .readers-card-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .readers-card-body {
        padding: 22px;
        background: #FFFFFF;
    }

    .readers-table {
        margin-bottom: 0;
    }

    .readers-table thead th {
        background: #F5F0E6;
        color: #493729;
        padding: 16px;
        border-bottom: 2px solid #D4A64A;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .readers-table tbody td {
        padding: 17px 16px;
        vertical-align: middle;
        color: #493729;
        border-color: #e9e0d3;
    }

    .readers-table tbody tr:hover {
        background: #faf7f0;
    }

    .reader-id {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #e2eee8;
        color: #145A45;
        font-weight: 700;
    }

    .reader-name {
        font-weight: 700;
        color: #0D4032;
    }

    .reader-contact {
        color: #655344;
    }

    .reader-contact i {
        color: #145A45;
        margin-right: 7px;
    }

    .btn-edit-reader {
        background: #f8edcf;
        color: #76551d;
        border: 1px solid #D4A64A;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-edit-reader:hover {
        background: #D4A64A;
        color: #493729;
    }

    .btn-delete-reader {
        background: #f7e5df;
        color: #963f2d;
        border: 1px solid #dfb6a9;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-delete-reader:hover {
        background: #963f2d;
        color: #FFFFFF;
    }

    .empty-readers {
        text-align: center;
        padding: 45px 15px;
        color: #766656;
    }

    .empty-readers i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        color: #145A45;
    }

    .empty-readers h5 {
        color: #0D4032;
        font-weight: 700;
    }

    .readers-page .badge.bg-secondary {
        background: #493729 !important;
    }

    @media(max-width: 768px) {
        .readers-card-body {
            padding: 12px;
        }

        .readers-header {
            align-items: flex-start;
        }
    }
</style>

<div class="readers-page">

    <div class="readers-header">
        <div>
            <h2>
                <i class="bi bi-people me-2"></i>
                Lista de Lectores
            </h2>
            <p>Administra la información de los lectores registrados.</p>
        </div>

        @if(auth()->user()->role == 'admin')
            <a href="{{ route('lectores.create') }}"
               class="btn btn-new-reader">
                <i class="bi bi-person-plus me-2"></i>
                Nuevo Lector
            </a>
        @endif
    </div>

    <div class="readers-card">

        <div class="readers-card-header">
            <h5>
                <i class="bi bi-person-vcard me-2"></i>
                Lectores registrados
            </h5>
        </div>

        <div class="readers-card-body">

            <div class="table-responsive">
                <table class="table readers-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($lectores as $lector)
                            <tr>
                                <td>
                                    <span class="reader-id">
                                        {{ $lector->id }}
                                    </span>
                                </td>

                                <td>
                                    <span class="reader-name">
                                        <i class="bi bi-person-circle me-2"></i>
                                        {{ $lector->nombre }} {{ $lector->apellido }}
                                    </span>
                                </td>

                                <td class="reader-contact">
                                    <i class="bi bi-envelope"></i>
                                    {{ $lector->email }}
                                </td>

                                <td class="reader-contact">
                                    <i class="bi bi-telephone"></i>
                                    {{ $lector->telefono ?: 'No registrado' }}
                                </td>

                                <td>
                                    @if(auth()->user()->role == 'admin')

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('lectores.edit', $lector) }}"
                                               class="btn btn-edit-reader btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar
                                            </a>

                                            <form action="{{ route('lectores.destroy', $lector) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar este lector?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-delete-reader btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                    Eliminar
                                                </button>

                                            </form>

                                        </div>

                                    @else
                                        <span class="badge bg-secondary">
                                            Solo lectura
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-readers">
                                        <i class="bi bi-person-x"></i>
                                        <h5>No hay lectores registrados</h5>
                                        <p class="mb-0">
                                            Los lectores que registres aparecerán aquí.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
