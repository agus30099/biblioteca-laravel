
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca DB - @yield('title', 'Sistema')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --verde: #145A45;
            --verde-oscuro: #0D4032;
            --dorado: #D4A64A;
            --crema: #F5F0E6;
            --cafe: #493729;
            --blanco: #FFFFFF;
        }

        body {
            background-color: var(--crema);
            color: var(--cafe);
            font-family: 'Segoe UI', sans-serif;
        }

        /* Barra de navegación */
        .navbar {
            background: linear-gradient(110deg, var(--verde-oscuro), var(--verde));
            padding: 13px 20px;
            box-shadow: 0 4px 14px rgba(30, 50, 35, .18);
        }

        .navbar-brand {
            color: var(--dorado) !important;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: .4px;
        }

        .navbar-brand i {
            margin-right: 7px;
        }

        .navbar .nav-link {
            color: #F5F0E6 !important;
            font-weight: 500;
            margin: 0 4px;
            padding: 9px 12px !important;
            border-radius: 8px;
            transition: .2s;
        }

        .navbar .nav-link:hover {
            background-color: rgba(255, 255, 255, .13);
            color: var(--dorado) !important;
        }

        .navbar-text {
            color: var(--crema) !important;
        }

        .navbar-toggler {
            border-color: var(--dorado);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 .2rem rgba(212, 166, 74, .25);
        }

        /* Botones generales */
        .btn-primary,
        .btn-success {
            background-color: var(--verde);
            border-color: var(--verde);
        }

        .btn-primary:hover,
        .btn-success:hover {
            background-color: var(--verde-oscuro);
            border-color: var(--verde-oscuro);
        }

        .btn-warning {
            background-color: var(--dorado);
            border-color: var(--dorado);
            color: var(--cafe);
        }

        .btn-warning:hover {
            background-color: #BD9138;
            border-color: #BD9138;
            color: var(--cafe);
        }

        .btn-danger {
            background-color: var(--cafe);
            border-color: var(--cafe);
        }

        .btn-danger:hover {
            background-color: #352719;
            border-color: #352719;
        }

        /* Tarjetas */
        .card {
            border: 1px solid rgba(73, 55, 41, .09);
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(73, 55, 41, .06);
        }

        /* Formularios */
        .form-control,
        .form-select {
            border-radius: 9px;
            border-color: #D8CCB9;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--verde);
            box-shadow: 0 0 0 .2rem rgba(20, 90, 69, .13);
        }

        /* Tablas */
        .table thead th {
            background-color: var(--verde);
            color: var(--blanco);
        }

        /* Alertas */
        .alert-success {
            background-color: #E4F0E8;
            color: var(--verde-oscuro);
            border-color: #B8D7C2;
        }

        /* Etiquetas */
        .badge.bg-info {
            background-color: var(--dorado) !important;
            color: var(--cafe) !important;
        }

        /* Contenido */
        main {
            min-height: 75vh;
        }

        @media (max-width: 991px) {
            .navbar .nav-link {
                margin: 3px 0;
            }

            .navbar-nav .btn {
                margin: 8px 0 !important;
            }
        }
    </style>
</head>

<body>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('libros.index') }}">
                <i class="bi bi-book-half"></i> Biblioteca DB
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">

                    @auth
                        @if(auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('categorias.index') }}">
                                    <i class="bi bi-tags me-1"></i> Categorías
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('autores.index') }}">
                                    <i class="bi bi-pen me-1"></i> Autores
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('lectores.index') }}">
                                    <i class="bi bi-people me-1"></i> Lectores
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('prestamos.index') }}">
                                    <i class="bi bi-journal-check me-1"></i> Préstamos
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reservas.index') }}">
                                    <i class="bi bi-bookmark me-1"></i> Mis Reservas
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('prestamos.index') }}">
                                    <i class="bi bi-journal-bookmark me-1"></i> Mis Préstamos
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('libros.index') }}">
                            <i class="bi bi-book me-1"></i> Catálogo Público
                        </a>
                    </li>

                </ul>

                <ul class="navbar-nav align-items-lg-center">

                    @auth
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ auth()->user()->name }}
                                <span class="badge bg-info text-dark">
                                    {{ auth()->user()->role }}
                                </span>
                            </span>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                Iniciar Sesión
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm mt-1 ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Registrarse
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido dinámico -->
    <main class="container mt-4 mb-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
