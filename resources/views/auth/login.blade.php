<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión | TechStock SENATI</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #334155;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            color: #ffffff;
        }

        .brand-icon-box {
            width: 58px;
            height: 58px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .login-body {
            padding: 2.25rem 2rem;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.45rem;
        }

        .form-control {
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.72rem 1rem;
            font-size: 0.93rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 10px;
            border: none;
            width: 100%;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }

        .demo-credential-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.78rem;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Header -->
    <div class="login-header">
        <div class="brand-icon-box">
            <i class="bi bi-cpu-fill"></i>
        </div>
        <h4 class="fw-bold mb-1 tracking-tight">TechStock</h4>
        <p class="mb-0 text-white-50 small">Sistema de Inventario de Accesorios Informáticos</p>
    </div>

    <!-- Body -->
    <div class="login-body">
        <!-- Notificaciones de Éxito o Error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show small d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" novalidate>
            @csrf

            <!-- Correo Electrónico -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #cbd5e1; border-right: none;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           value="{{ old('email', 'admin@ejemplo.com') }}"
                           placeholder="ejemplo@correo.com"
                           required
                           autofocus>
                </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #cbd5e1; border-right: none;">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           placeholder="••••••••"
                           required>
                </div>
            </div>

            <!-- Recordarme -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-muted" for="remember">
                        Recordarme en este equipo
                    </label>
                </div>
            </div>

            <!-- Botón Login -->
            <button type="submit" class="btn btn-login mb-4">
                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar al Sistema
            </button>
        </form>

        <!-- Credenciales Demo -->
        <div class="demo-credential-box">
            <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-1">
                <i class="bi bi-key-fill text-primary"></i> Acceso del Administrador:
            </div>
            <div><strong>Usuario:</strong> <code>admin@ejemplo.com</code></div>
            <div><strong>Contraseña:</strong> <code>admin123</code></div>
            <div class="mt-1 text-muted" style="font-size: 0.72rem;"><strong>Titular:</strong> Gabriel Tito Jara Ccoyso</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
