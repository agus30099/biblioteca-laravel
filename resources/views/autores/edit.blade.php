@extends('layouts.app')

@section('title', 'Editar Autor')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark"><h4>Editar Autor</h4></div>
                <div class="card-body">
                    <form action="{{ route('autores.update', $autor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nombre del Autor:</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $autor->nombre }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nacionalidad:</label>
                            <input type="text" name="nacionalidad" class="form-control" value="{{ $autor->nacionalidad }}">
                        </div>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection