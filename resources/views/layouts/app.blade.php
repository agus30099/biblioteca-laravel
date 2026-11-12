<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - @yield('title', 'Sistema')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('categorias.index') }}"><i class="bi bi-book"></i> Biblioteca</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth <!-- Solo muestra los enlaces si está logueado -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}">Categorías</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('libros.index') }}">Libros</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('lectores.index') }}">Lectores</a></li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <span class="navbar-text me-3">Hola, {{ auth()->user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">Salir</button>
                            </form>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenido Dinámico -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>