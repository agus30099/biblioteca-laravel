
@extends('layouts.app')

@section('title', 'Autores')

@section('content')

<style>
    .authors-page {
        color: #493729;
    }

    .authors-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 28px;
    }

    .authors-header h2 {
        color: #0D4032;
        font-weight: 800;
        margin: 0;
    }

    .authors-header p {
        color: #786653;
        margin: 5px 0 0;
    }

    .btn-new-author {
        background: #145A45;
        color: white;
        border: none;
        padding: 11px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-new-author:hover {
        background: #0D4032;
        color: white;
        transform: translateY(-2px);
    }

    .authors-card {
        background: white;
        border: 1px solid #E8E0D2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(73,55,41,.08);
    }

    .authors-card-header {
        background: #145A45;
        color: white;
        padding: 19px 22px;
        border-bottom: 3px solid #D4A64A;
    }

    .authors-card-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .authors-card-body {
        padding: 22px;
    }

    .authors-table {
        margin-bottom: 0;
    }

    .authors-table thead th {
        background: #F5F0E6;
        color: #493729;
        padding: 16px;
        border-bottom: 2px solid #D4A64A;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .authors-table tbody td {
        padding: 17px 16px;
        vertical-align: middle;
        color: #493729;
        border-color: #E8E0D2;
    }

    .authors-table tbody tr:hover {
        background: #FAF7F0;
    }

    .author-id {
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

    .author-name {
        font-weight: 700;
        color: #0D4032;
    }

    .author-country {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #F5F0E6;
        color: #493729;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #E8E0D2;
        white-space: nowrap;
    }

    .author-flag {
        width: 32px;
        height: 22px;
        object-fit: cover;
        border-radius: 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }

    .btn-edit-author {
        background: #F5E9CC;
        color: #72501C;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-edit-author:hover {
        background: #D4A64A;
        color: #493729;
    }

    .btn-delete-author {
        background: #FCE8E5;
        color: #A83232;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
    }

    .btn-delete-author:hover {
        background: #F4C9C3;
        color: #842525;
    }

    .empty-authors {
        text-align: center;
        padding: 45px 15px;
        color: #786653;
    }

    .empty-authors i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        color: #B39B7B;
    }

    .empty-authors h5 {
        color: #0D4032;
        font-weight: 700;
    }

    @media(max-width: 768px) {
        .authors-card-body {
            padding: 12px;
        }

        .authors-header {
            align-items: flex-start;
        }
    }
</style>

@php
    $paises = [
        'mexicana' => 'mx',
        'mexicano' => 'mx',
        'méxico' => 'mx',
        'mexico' => 'mx',
        'colombiana' => 'co',
        'colombiano' => 'co',
        'colombia' => 'co',
        'española' => 'es',
        'español' => 'es',
        'españa' => 'es',
        'argentina' => 'ar',
        'argentino' => 'ar',
        'estadounidense' => 'us',
        'estados unidos' => 'us',
        'chilena' => 'cl',
        'chileno' => 'cl',
        'chile' => 'cl',
        'peruana' => 'pe',
        'peruano' => 'pe',
        'perú' => 'pe',
        'peru' => 'pe',
        'francesa' => 'fr',
        'francés' => 'fr',
        'francia' => 'fr',
        'italiana' => 'it',
        'italiano' => 'it',
        'italia' => 'it',
        'alemana' => 'de',
        'alemán' => 'de',
        'alemania' => 'de',
        'inglesa' => 'gb',
        'inglés' => 'gb',
        'reino unido' => 'gb',
        'brasileña' => 'br',
        'brasileño' => 'br',
        'brasil' => 'br',
        'japonesa' => 'jp',
        'japonés' => 'jp',
        'japón' => 'jp',
        'rusa' => 'ru',
        'ruso' => 'ru',
        'rusia' => 'ru',
        'canadiense' => 'ca',
        'canadá' => 'ca',
        'canada' => 'ca',
        'portuguesa' => 'pt',
        'portugués' => 'pt',
        'portugal' => 'pt',
        'griega' => 'gr',
        'griego' => 'gr',
        'grecia' => 'gr',
        'irlandesa' => 'ie',
        'irlandés' => 'ie',
        'irlanda' => 'ie',
        'suiza' => 'ch',
        'suizo' => 'ch',
        'suiza' => 'ch',
        'británica' => 'gb',
        'britanica' => 'gb',
        'israelí' => 'il',
        'israeli' => 'il',
    ];
@endphp

<div class="authors-page">

    <div class="authors-header">
        <div>
            <h2>
                <i class="bi bi-people me-2"></i>
                Catálogo de Autores
            </h2>
            <p>Consulta y administra los autores de la biblioteca.</p>
        </div>

        @if(auth()->user()->isStaff())
            <a href="{{ route('autores.create') }}"
               class="btn btn-new-author">
                <i class="bi bi-person-plus me-2"></i>
                Nuevo Autor
            </a>
        @endif
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <strong>
                <i class="bi bi-exclamation-circle me-2"></i>
                Hay errores en el formulario:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="authors-card">

        <div class="authors-card-header">
            <h5>
                <i class="bi bi-person-lines-fill me-2"></i>
                Autores registrados
            </h5>
        </div>

        <div class="authors-card-body">

            <div class="table-responsive">
                <table class="table authors-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($autores as $autor)

                            @php
                                $nacionalidad = mb_strtolower(trim($autor->nacionalidad ?? ''));
                                $codigoPais = $paises[$nacionalidad] ?? null;
                            @endphp

                            <tr>
                                <td>
                                    <span class="author-id">
                                        {{ $autor->id }}
                                    </span>
                                </td>

                                <td>
                                    <span class="author-name">
                                        {{ $autor->nombre }}
                                    </span>
                                </td>

                                <td>
                                    <span class="author-country">

                                        @if($codigoPais)
                                            <img
                                                src="https://flagcdn.com/w40/{{ $codigoPais }}.png"
                                                alt="Bandera de {{ $autor->nacionalidad }}"
                                                class="author-flag"
                                            >
                                        @else
                                            <i class="bi bi-globe2"></i>
                                        @endif

                                        {{ $autor->nacionalidad ?? 'No especificada' }}

                                    </span>
                                </td>

                                <td>
                                    @if(auth()->user()->isStaff())

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('autores.edit', $autor) }}"
                                               class="btn btn-edit-author btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar
                                            </a>

                                            <form action="{{ route('autores.destroy', $autor) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar este autor?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-delete-author btn-sm">
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
                                    <div class="empty-authors">
                                        <i class="bi bi-person-x"></i>
                                        <h5>No hay autores registrados</h5>
                                        <p class="mb-0">
                                            Los autores que agregues aparecerán aquí.
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
