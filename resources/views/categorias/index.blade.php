
@extends('layouts.app')

@section('title', 'Categorías')

@section('content')

<style>
    .categories-page {
        color: #493729;
    }

    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 28px;
    }

    .categories-header h2 {
        color: #0D4032;
        font-weight: 800;
        margin: 0;
    }

    .categories-header p {
        color: #786653;
        margin: 5px 0 0;
    }

    .btn-new-category {
        background: #145A45;
        color: white;
        border: none;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-new-category:hover {
        background: #0D4032;
        color: white;
        transform: translateY(-2px);
    }

    .categories-card {
        background: white;
        border: 1px solid #E8E0D2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(73, 55, 41, .08);
    }

    .categories-card-header {
        background: #145A45;
        color: white;
        padding: 19px 22px;
        border-bottom: 3px solid #D4A64A;
    }

    .categories-card-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .categories-card-body {
        padding: 22px;
    }

    .categories-table {
        margin-bottom: 0;
    }

    .categories-table thead th {
        background: #F5F0E6;
        color: #493729;
        padding: 16px;
        border-bottom: 2px solid #D4A64A;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .categories-table tbody td {
        padding: 17px 16px;
        vertical-align: middle;
        color: #493729;
        border-color: #E8E0D2;
    }

    .categories-table tbody tr:hover {
        background: #FAF7F0;
    }

    .category-id {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #E4EFE9;
        color: #145A45;
        font-weight: 700;
    }

    .category-name {
        font-weight: 700;
        color: #0D4032;
    }

    .btn-edit-category {
        background: #F5E9CC;
        color: #72501C;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-edit-category:hover {
        background: #D4A64A;
        color: #493729;
    }

    .btn-delete-category {
        background: #FCE8E5;
        color: #A83232;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-delete-category:hover {
        background: #F4C9C3;
        color: #842525;
    }

    .empty-categories {
        text-align: center;
        padding: 45px 15px;
        color: #786653;
    }

    .empty-categories i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        color: #B39B7B;
    }

    .empty-categories h5 {
        color: #0D4032;
        font-weight: 700;
    }

    @media(max-width: 768px) {
        .categories-card-body {
            padding: 12px;
        }

        .categories-header {
            align-items: flex-start;
        }
    }
</style>

<div class="categories-page">

    <div class="categories-header">
        <div>
            <h2>
                <i class="bi bi-tags me-2"></i>
                Categorías
            </h2>
            <p>Administra y organiza las categorías de la biblioteca.</p>
        </div>

        @if(auth()->user()->role == 'admin')
            <a href="{{ route('categorias.create') }}"
               class="btn btn-new-category">
                <i class="bi bi-plus-circle me-2"></i>
                Nueva Categoría
            </a>
        @endif
    </div>

    <div class="categories-card">

        <div class="categories-card-header">
            <h5>
                <i class="bi bi-collection me-2"></i>
                Categorías registradas
            </h5>
        </div>

        <div class="categories-card-body">

            <div class="table-responsive">
                <table class="table categories-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categorias as $categoria)
                            <tr>
                                <td>
                                    <span class="category-id">
                                        {{ $categoria->id }}
                                    </span>
                                </td>

                                <td>
                                    <span class="category-name">
                                        {{ $categoria->nombre }}
                                    </span>
                                </td>

                                <td>
                                    {{ $categoria->descripcion ?: 'Sin descripción' }}
                                </td>

                                <td>
                                    @if(auth()->user()->role == 'admin')

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('categorias.edit', $categoria) }}"
                                               class="btn btn-edit-category btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar
                                            </a>

                                            <form action="{{ route('categorias.destroy', $categoria) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-delete-category btn-sm">
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
                                <td colspan="4">
                                    <div class="empty-categories">
                                        <i class="bi bi-folder-x"></i>
                                        <h5>No hay categorías registradas</h5>
                                        <p class="mb-0">
                                            Las categorías que agregues aparecerán aquí.
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
