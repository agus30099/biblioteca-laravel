@extends('layouts.app')

@section('title', 'Editar Libro')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4>Editar Libro</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('libros.update', $libro) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Título:</label>
                                <input type="text" name="titulo" class="form-control" value="{{ $libro->titulo }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Autor:</label>
                                <select name="autor_id" class="form-select" required>
                                    <option value="">Seleccione un autor...</option>
                                    @foreach($autores as $autor)
                                        <option value="{{ $autor->id }}" {{ $libro->autor_id == $autor->id ? 'selected' : '' }}>
                                            {{ $autor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ISBN:</label>
                                <input type="text" name="isbn" class="form-control" value="{{ $libro->isbn }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock:</label>
                                <input type="number" name="stock" class="form-control" value="{{ $libro->stock }}" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Categoría:</label>
                                <select name="categoria_id" class="form-select" required>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ $libro->categoria_id == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">Actualizar Libro</button>
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection