
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Biblioteca</title>

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
                url("/images/biblioteca.jpg") center / cover no-repeat;
            filter: blur(5px);
            z-index: -1;
        }

        .login-card {
            background-color: #F5F0E6;
            border: 2px solid #D4A64A;
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            max-width: 430px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #0D4032, #145A45);
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-bottom: 5px solid #D4A64A;
        }

        .login-header i {
            font-size: 45px;
            color: #D4A64A;
        }

        .login-header h2 {
            margin-top: 10px;
            font-weight: bold;
        }

        .login-header p {
            color: #F5F0E6;
        }

        .login-body {
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

        .btn-login {
            background-color: #145A45;
            color: white;
            border: none;
            border-radius: 9px;
            padding: 13px;
            font-weight: bold;
            width: 100%;
            transition: 0.2s;
        }

        .btn-login:hover {
            background-color: #0D4032;
            color: white;
        }

        .register-link {
            color: #145A45;
            font-weight: bold;
            text-decoration: none;
        }

        .register-link:hover {
            color: #0D4032;
            text-decoration: underline;
        }

        .login-footer {
            text-align: center;
            color: #8b7968;
            font-size: 13px;
            padding: 0 20px 22px;
        }

        @media (max-width: 480px) {
            .login-body {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="login-header">
            <i class="bi bi-book-half"></i>
            <h2>Bienvenido</h2>
            <p class="mb-0">Inicia sesión en tu biblioteca</p>
        </div>

        <div class="login-body">

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Ingresa tu correo"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Ingresa tu contraseña"
                        required
                    >
                </div>

                @error('email')
                    <div class="alert alert-danger py-2">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Iniciar sesión
                </button>
            </form>

            <div class="text-center mt-4">
                <span>¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}" class="register-link">
                    Registrar cuenta
                </a>
            </div>

        </div>

        <div class="login-footer">
            <i class="bi bi-book-half me-1" style="color: #D4A64A;"></i>
            Sistema de Gestión de Biblioteca
        </div>

    </div>

</body>
</html>
