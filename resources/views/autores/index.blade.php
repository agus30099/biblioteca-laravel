@extends('layouts.app')

@section('title', 'Autores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catálogo de Autores</h2>
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('autores.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Nuevo Autor
            </a>
        @endif
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Nacionalidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($autores as $autor)
                        <tr>
                            <td>{{ $autor->id }}</td>
                            <td>{{ $autor->nombre }}</td>
                            <td>{{ $autor->nacionalidad ?? 'No especificada' }}</td>
                            <td>
                                @if(auth()->user()->role == 'admin')
                                    <a href="{{ route('autores.edit', $autor) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('autores.destroy', $autor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar autor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">Solo lectura</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No hay autores registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection