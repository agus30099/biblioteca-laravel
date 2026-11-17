@extends('layouts.app')

@section('title', 'Devolución')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4>Registrar Devolución</h4>
                </div>
                <div class="card-body">
                    <p><strong>Libro:</strong> {{ $prestamo->libro->titulo }}</p>
                    <p><strong>Lector:</strong> {{ $prestamo->lector->nombre }} {{ $prestamo->lector->apellido }}</p>
                    
                    <form action="{{ route('prestamos.update', $prestamo) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="estado" value="Devuelto">
                        
                        <div class="alert alert-info">
                            Al confirmar, se registrará la fecha de hoy y el libro volverá al inventario.
                        </div>
                        
                        <button type="submit" class="btn btn-success">Confirmar Devolución</button>
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection