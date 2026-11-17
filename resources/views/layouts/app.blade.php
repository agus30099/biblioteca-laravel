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
            <!-- El logo redirige al inicio o al catálogo si es invitado -->
            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('libros.index') }}">
                <i class="bi bi-book"></i> Biblioteca
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->role == 'admin')
                            <!-- Menú Exclusivo para Administradores -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}">Categorías</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('autores.index') }}">Autores</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('lectores.index') }}">Lectores</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('prestamos.index') }}">Préstamos</a></li>
                        @else
                            <!-- Menú Exclusivo para Lectores -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('reservas.index') }}">Mis Reservas</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('prestamos.index') }}">Mis Préstamos</a></li>
                        @endif 
                    @endauth

                    <!-- El Catálogo es visible para TODOS (Invitados y Logueados) -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('libros.index') }}">Catálogo Público</a></li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                {{ auth()->user()->name }} 
                                <span class="badge bg-info text-dark">{{ auth()->user()->role }}</span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">Salir</button>
                            </form>
                        </li>
                    @else
                        <!-- Botones para invitados -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
                        <li class="nav-item"><a class="btn btn-warning btn-sm mt-1 ms-2" href="{{ route('register') }}">Registrarse</a></li>
                    @endauth
                </ul>
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