@extends('layouts.app')

@section('title', 'Lectores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Lectores</h2>
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('lectores.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Nuevo Lector
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
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
                            <td>{{ $lector->id }}</td>
                            <td>{{ $lector->nombre }} {{ $lector->apellido }}</td>
                            <td>{{ $lector->email }}</td>
                            <td>{{ $lector->telefono }}</td>
                            <td>
                                @if(auth()->user()->role == 'admin')
                                    <a href="{{ route('lectores.edit', $lector) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('lectores.destroy', $lector) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar lector?')">
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
                        <tr><td colspan="5" class="text-center">No hay lectores.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection