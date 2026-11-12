@extends('layouts.app')

@section('title', 'Nuevo Lector')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white"><h4>Registrar Lector</h4></div>
                <div class="card-body">
                    <form action="{{ route('lectores.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido:</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('lectores.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection