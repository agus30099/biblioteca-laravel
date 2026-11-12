@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4>Editar Categoría</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Laravel simula el método PUT -->
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $categoria->nombre }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción:</label>
                            <textarea name="descripcion" class="form-control" rows="3">{{ $categoria->descripcion }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection