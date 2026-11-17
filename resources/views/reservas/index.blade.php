@extends('layouts.app')

@section('title', 'Lista de Espera')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Espera (Reservas)</h2>
    </div>

    <!-- Mensajes de error o éxito -->
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
                        <th>Libro</th>
                        @if(auth()->user()->role == 'admin') <th>Lector</th> @endif
                        <th>Fecha Reserva</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            <td>{{ $reserva->libro->titulo }}</td>
                            @if(auth()->user()->role == 'admin') 
                                <td>{{ $reserva->lector->nombre }} {{ $reserva->lector->apellido }}</td> 
                            @endif
                            <td>{{ $reserva->fecha_reserva }}</td>
                            <td>
                                <span class="badge bg-{{ $reserva->estado == 'Pendiente' ? 'warning text-dark' : 'success' }}">
                                    {{ $reserva->estado }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay reservas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection