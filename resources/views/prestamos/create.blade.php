@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Registrar Préstamo de Libro</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('prestamos.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Seleccionar Libro (Solo con stock):</label>
                                <select name="libro_id" class="form-select" required>
                                    <option value="">Seleccione un libro...</option>
                                    @foreach($libros as $libro)
                                        <option value="{{ $libro->id }}">{{ $libro->titulo }} (Stock: {{ $libro->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Seleccionar Lector:</label>
                                <select name="lector_id" class="form-select" required>
                                    <option value="">Seleccione un lector...</option>
                                    @foreach($lectores as $lector)
                                        <option value="{{ $lector->id }}">{{ $lector->nombre }} {{ $lector->apellido }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Préstamo:</label>
                            <input type="date" name="fecha_prestamo" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection