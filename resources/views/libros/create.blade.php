@extends('layouts.app')

@section('title', 'Nuevo Libro')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Registrar Nuevo Libro</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('libros.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Título:</label>
                                <input type="text" name="titulo" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Autor:</label>
                                <input type="text" name="autor" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ISBN:</label>
                                <input type="text" name="isbn" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock (Cantidad):</label>
                                <input type="number" name="stock" class="form-control" min="0" value="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Categoría:</label>
                                <select name="categoria_id" class="form-select" required>
                                    <option value="">Seleccione una...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Libro</button>
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection