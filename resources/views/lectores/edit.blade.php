@extends('layouts.app')

@section('title', 'Editar Lector')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark"><h4>Editar Lector</h4></div>
                <div class="card-body">
                    <form action="{{ route('lectores.update', $lector) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $lector->nombre }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido:</label>
                            <input type="text" name="apellido" class="form-control" value="{{ $lector->apellido }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ $lector->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $lector->telefono }}">
                        </div>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('lectores.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection