
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Biblioteca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 35px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            color: #493729;
            background: #0D4032;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: -15px;
            background:
                linear-gradient(rgba(13, 64, 50, 0.35), rgba(13, 64, 50, 0.50)),
                url("https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=2000&q=85")
                center / cover no-repeat;
            filter: blur(5px);
            z-index: -1;
        }

        .register-card {
            background-color: #F5F0E6;
            border: 2px solid #D4A64A;
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
        }

        .register-header {
            background: linear-gradient(135deg, #0D4032, #145A45);
            color: white;
            text-align: center;
            padding: 28px 20px;
            border-bottom: 5px solid #D4A64A;
        }

        .register-header i {
            font-size: 45px;
            color: #D4A64A;
        }

        .register-header h2 {
            margin-top: 8px;
            font-weight: bold;
        }

        .register-header p {
            color: #F5F0E6;
        }

        .register-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #493729;
        }

        .form-control {
            border-radius: 9px;
            padding: 12px;
            border: 1px solid #d8d0c3;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: #145A45;
            box-shadow: 0 0 0 0.2rem rgba(20, 90, 69, 0.15);
        }

        .btn-register {
            background-color: #145A45;
            color: white;
            border: none;
            border-radius: 9px;
            padding: 13px;
            font-weight: bold;
            width: 100%;
            transition: 0.2s;
        }

        .btn-register:hover {
            background-color: #0D4032;
            color: white;
        }

        .login-link {
            color: #145A45;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link:hover {
            color: #0D4032;
            text-decoration: underline;
        }

        .register-footer {
            text-align: center;
            color: #8b7968;
            font-size: 13px;
            padding: 0 20px 22px;
        }

        @media (max-width: 480px) {
            .register-body {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="register-card">

        <div class="register-header">
            <i class="bi bi-book-half"></i>
            <h2>Crear cuenta</h2>
            <p class="mb-0">Únete a nuestra biblioteca</p>
        </div>

        <div class="register-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre completo</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Ingresa tu nombre completo"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Ingresa tu correo"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Crea una contraseña"
                        required
                        minlength="6"
                    >
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        Confirmar contraseña
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        placeholder="Repite tu contraseña"
                        required
                        minlength="6"
                    >
                </div>

                <button type="submit" class="btn btn-register mb-3">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Crear cuenta
                </button>

                <div class="text-center">
                    <span>¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="login-link">
                        Inicia sesión
                    </a>
                </div>

            </form>

        </div>

        <div class="register-footer">
            <i class="bi bi-book-half me-1" style="color: #D4A64A;"></i>
            Sistema de Gestión de Biblioteca
        </div>

    </div>

</body>
</html>
