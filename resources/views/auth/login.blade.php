<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Tienda ISOATLAS</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #7c3aed;
            --accent: #06b6d4;
            --dark-bg: #0b1120;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: 
                radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.28), transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(139, 92, 246, 0.25), transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.15), transparent 60%),
                linear-gradient(135deg, #0b1120 0%, #0f172a 40%, #1e1b4b 100%);
            background-attachment: fixed;
            color: #1e293b;
        }

        /* Ambient floating light */
        .ambient-glow {
            position: fixed;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.22) 0%, rgba(0,0,0,0) 70%);
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            pointer-events: none;
            filter: blur(45px);
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(22px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 26px;
            padding: 38px 34px;
            box-shadow: 
                0 25px 60px -15px rgba(0, 0, 0, 0.45),
                0 0 0 1px rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        /* Top multicolored border */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2563eb, #7c3aed, #06b6d4);
        }

        /* Store Badge Pill */
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.3px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .brand-badge .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.8; }
        }

        /* Hero Icon Badge */
        .icon-badge {
            width: 74px;
            height: 74px;
            margin: 0 auto 16px auto;
            border-radius: 22px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 32px;
            box-shadow: 
                0 14px 28px -6px rgba(37, 99, 235, 0.45),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
            position: relative;
            transition: transform 0.3s ease;
        }

        .icon-badge:hover {
            transform: scale(1.06) rotate(3deg);
        }

        .icon-badge .badge-bubble {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 26px;
            height: 26px;
            background: #06b6d4;
            border: 3px solid #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .title-text {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .subtitle-text {
            color: #64748b;
            font-size: 0.92rem;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        /* Form Controls */
        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
            border-radius: 14px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            transition: all 0.25s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .input-group-custom:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .input-group-custom .input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            color: #94a3b8;
            font-size: 1.05rem;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            transition: color 0.2s ease;
        }

        .input-group-custom:focus-within .input-icon {
            color: #2563eb;
            background: #eff6ff;
        }

        .input-group-custom input {
            border: none;
            outline: none;
            padding: 13px 16px;
            font-size: 0.95rem;
            color: #0f172a;
            width: 100%;
            font-family: inherit;
            font-weight: 500;
        }

        .input-group-custom input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Password toggle button */
        .btn-toggle-eye {
            border: none;
            background: transparent;
            color: #94a3b8;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .btn-toggle-eye:hover {
            color: #2563eb;
        }

        /* Link forgot password */
        .forgot-link {
            font-size: 0.82rem;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .forgot-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Custom Checkbox */
        .remember-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 18px 0 22px 0;
            user-select: none;
        }

        .remember-checkbox-wrapper input[type="checkbox"] {
            width: 17px;
            height: 17px;
            border-radius: 5px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .remember-checkbox-wrapper label {
            font-size: 0.86rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            margin: 0;
        }

        /* Submit Button */
        .btn-submit-action {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #7c3aed 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 22px -5px rgba(37, 99, 235, 0.45);
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .btn-submit-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -5px rgba(37, 99, 235, 0.55);
            color: #ffffff;
        }

        .btn-submit-action:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-submit-action:disabled {
            opacity: 0.85;
            cursor: not-allowed;
            filter: grayscale(0.2);
        }

        /* Modern Alerts */
        .modern-alert {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.88rem;
            line-height: 1.45;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success-modern {
            background: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            color: #065f46;
        }

        .alert-success-modern .alert-icon {
            color: #10b981;
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-error-modern {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #991b1b;
        }

        .alert-error-modern .alert-icon {
            color: #ef4444;
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Security Assurance chip */
        .security-badge {
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 0.76rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .security-badge i {
            color: #10b981;
        }
    </style>
</head>
<body>

<div class="ambient-glow"></div>

<div class="auth-container">
    <div class="auth-card">

        <!-- Store brand header pill -->
        <div class="text-center">
            <div class="brand-badge">
                <span class="status-dot"></span>
                <span>Tienda ISOATLAS</span>
                <span class="text-muted">•</span>
                <span class="text-primary fw-semibold"><i class="fa-solid fa-bolt me-1"></i>Electrónica</span>
            </div>
        </div>

        <!-- Glowing store icon -->
        <div class="icon-badge">
            <i class="fa-solid fa-cubes"></i>
            <div class="badge-bubble">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <!-- Header Titles -->
        <div class="text-center">
            <h1 class="title-text">Iniciar Sesión</h1>
            <p class="subtitle-text">
                Ingresa al sistema de ventas, compras e inventario de accesorios electrónicos.
            </p>
        </div>

        <!-- Status Message (e.g. from password reset) -->
        @if(session('status'))
            <div class="modern-alert alert-success-modern">
                <i class="fa-solid fa-circle-check alert-icon"></i>
                <div>
                    <div class="fw-bold mb-1" style="font-size: 0.92rem;">¡Excelente!</div>
                    @if(session('status') == 'passwords.reset' || str_contains(strtolower(session('status')), 'reset'))
                        Tu contraseña ha sido restablecida correctamente. Ya puedes iniciar sesión con tu nueva clave.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="modern-alert alert-error-modern">
                <i class="fa-solid fa-circle-exclamation alert-icon"></i>
                <div>
                    <div class="fw-bold mb-1" style="font-size: 0.92rem;">Credenciales incorrectas</div>
                    <div>
                        @if($errors->has('email'))
                            El correo electrónico o la contraseña ingresada no coinciden con nuestros registros.
                        @else
                            {{ $errors->first() }}
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label-custom">
                    <i class="fa-solid fa-envelope text-primary"></i>
                    Correo electrónico
                </label>
                <div class="input-group-custom">
                    <span class="input-icon">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="tu-correo@empresa.com"
                        required
                        autofocus
                        autocomplete="username">
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label-custom mb-0">
                        <i class="fa-solid fa-lock text-primary"></i>
                        Contraseña
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <div class="input-group-custom">
                    <span class="input-icon">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                    <button type="button" class="btn-toggle-eye" id="togglePassword" title="Mostrar/ocultar contraseña">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="remember-checkbox-wrapper">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    Mantener sesión iniciada
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit-action" id="btnSubmit">
                <i class="fa-solid fa-arrow-right-to-bracket" id="btnIcon"></i>
                <span id="btnText">Ingresar al Sistema</span>
            </button>

            <!-- Security Assurance -->
            <div class="security-badge">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Conexión cifrada y protegida por ISOATLAS</span>
            </div>

        </form>

    </div>
</div>

<script>
    // Eye toggle for password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fa-regular fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fa-regular fa-eye';
            }
        });
    }

    // Submit loading state to prevent double clicks
    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');

    if (loginForm && btnSubmit) {
        loginForm.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnIcon.className = 'fa-solid fa-circle-notch fa-spin';
            btnText.textContent = 'Iniciando sesión...';
        });
    }
</script>

</body>
</html>
