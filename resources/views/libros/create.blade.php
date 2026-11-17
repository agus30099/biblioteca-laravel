
@extends('layouts.app')

@section('title', 'Nuevo Libro')

@section('content')

<style>
    .book-create-page {
        color: #493729;
    }

    .book-create-card {
        background: #FFFFFF;
        border: 1px solid #e5dccd;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(73,55,41,.09);
    }

    .book-create-header {
        background: #0D4032;
        color: #FFFFFF;
        padding: 20px 24px;
        border-bottom: 3px solid #D4A64A;
    }

    .book-create-header h4 {
        margin: 0;
        font-weight: 700;
    }

    .book-create-header p {
        color: #e6ddc9;
        margin: 5px 0 0;
        font-size: 14px;
    }

    .book-create-body {
        padding: 26px;
    }

    .book-create-page .form-label {
        color: #493729;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .book-create-page .form-control,
    .book-create-page .form-select {
        border: 1px solid #d8cbbb;
        border-radius: 9px;
        padding: 11px 13px;
        color: #493729;
        background-color: #FFFFFF;
    }

    .book-create-page .form-control:focus,
    .book-create-page .form-select:focus {
        border-color: #145A45;
        box-shadow: 0 0 0 3px rgba(20,90,69,.12);
    }

    .book-create-page .form-text {
        color: #766656;
    }

    .btn-save-book {
        background: #145A45;
        color: #FFFFFF;
        border: 1px solid #D4A64A;
        border-radius: 9px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-save-book:hover {
        background: #0D4032;
        color: #FFFFFF;
    }

    .btn-cancel-book {
        background: #F5F0E6;
        color: #493729;
        border: 1px solid #d8cbbb;
        border-radius: 9px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-cancel-book:hover {
        background: #e9dfce;
        color: #493729;
    }

    .book-create-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(212,166,74,.16);
        color: #D4A64A;
        border-radius: 12px;
        font-size: 23px;
    }

    @media(max-width: 768px) {
        .book-create-body {
            padding: 18px;
        }
    }
</style>

<div class="book-create-page">

    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">

            <div class="book-create-card">

                <div class="book-create-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="book-create-icon">
                            <i class="bi bi-journal-plus"></i>
                        </div>

                        <div>
                            <h4>Registrar Nuevo Libro</h4>
                            <p>Agrega un nuevo título al catálogo de la biblioteca.</p>
                        </div>
                    </div>
                </div>

                <div class="book-create-body">

                    <form id="formLibro" action="{{ route('libros.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Título:</label>
                                <input type="text"
                                       name="titulo"
                                       class="form-control"
                                       placeholder="Nombre del libro"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Autor:</label>
                                <input type="text"
                                       name="autor"
                                       class="form-control"
                                       placeholder="Nombre del autor"
                                       required>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">ISBN:</label>
                                <input type="text"
                                       name="isbn"
                                       class="form-control"
                                       placeholder="Código ISBN"
                                       required>
                                <div class="form-text">Mínimo 5 caracteres.</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock (Cantidad):</label>
                                <input type="number"
                                       name="stock"
                                       class="form-control"
                                       min="0"
                                       value="1"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Categoría:</label>
                                <select name="categoria_id" class="form-select" required>
                                    <option value="">Seleccione una...</option>

                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                        </div>

                        <hr style="border-color:#e5dccd; margin: 15px 0 22px;">

                        <div class="d-flex flex-wrap gap-2">

                            <button type="submit" class="btn btn-save-book">
                                <i class="bi bi-check-circle me-2"></i>
                                Guardar Libro
                            </button>

                            <a href="{{ route('libros.index') }}" class="btn btn-cancel-book">
                                <i class="bi bi-arrow-left me-2"></i>
                                Cancelar
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script>
    document.getElementById('formLibro').addEventListener('submit', function(e) {
        const isbn = document.querySelector('input[name="isbn"]').value;

        if(isbn.length < 5) {
            e.preventDefault();
            alert('El ISBN debe tener al menos 5 caracteres.');
        }
    });
</script>

@endsection
