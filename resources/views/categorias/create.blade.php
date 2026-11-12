@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Crear Categoría</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción (opcional):</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
