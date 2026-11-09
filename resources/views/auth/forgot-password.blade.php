<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Tienda ISOATLAS</title>

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

        /* Ambient floating particles background */
        .ambient-glow {
            position: fixed;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(0,0,0,0) 70%);
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
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
                transform: translateY(20px) scale(0.98);
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

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2563eb, #7c3aed, #06b6d4);
        }

        /* Brand Pill */
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
            margin-bottom: 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .brand-badge .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        /* Icon Badge */
        .icon-badge {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px auto;
            border-radius: 20px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 30px;
            box-shadow: 
                0 14px 28px -6px rgba(37, 99, 235, 0.45),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
            position: relative;
            transition: transform 0.3s ease;
        }

        .icon-badge:hover {
            transform: scale(1.06) rotate(4deg);
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
            font-size: 1.55rem;
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

        /* Mini Steps Card */
        .steps-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            color: #475569;
            font-weight: 600;
        }

        .step-num {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #334155;
            font-size: 0.7rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-item.active .step-num {
            background: #2563eb;
            color: #ffffff;
        }

        .step-separator {
            color: #cbd5e1;
            font-size: 0.75rem;
        }

        /* Inputs & Groups */
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

        /* Submit Button */
        .btn-submit-action {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #7c3aed 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            padding: 14px 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 22px -5px rgba(37, 99, 235, 0.45);
            transition: all 0.25s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-submit-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -5px rgba(37, 99, 235, 0.55);
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

        /* Return Link */
        .back-link-wrapper {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .back-link i {
            transition: transform 0.2s ease;
        }

        .back-link:hover {
            color: #2563eb;
        }

        .back-link:hover i {
            transform: translateX(-4px);
        }

        /* Modern Alerts */
        .modern-alert {
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
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
            font-size: 1.3rem;
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
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Security footer chip */
        .security-badge {
            margin-top: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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

        <!-- Top store header badge -->
        <div class="text-center">
            <div class="brand-badge">
                <span class="status-dot"></span>
                <span>Tienda ISOATLAS</span>
                <span class="text-muted">•</span>
                <span class="text-primary fw-semibold"><i class="fa-solid fa-bolt me-1"></i>Electrónica</span>
            </div>
        </div>

        <!-- Central Shield/Key icon -->
        <div class="icon-badge">
            <i class="fa-solid fa-shield-halved"></i>
            <div class="badge-bubble">
                <i class="fa-solid fa-key"></i>
            </div>
        </div>

        <!-- Heading -->
        <div class="text-center">
            <h1 class="title-text">¿Olvidaste tu contraseña?</h1>
            <p class="subtitle-text">
                No te preocupes. Escribe el correo electrónico registrado y te enviaremos un enlace seguro para restablecerla.
            </p>
        </div>

        <!-- Mini Step Guide -->
        <div class="steps-box">
            <div class="step-item active">
                <span class="step-num">1</span>
                <span>Tu correo</span>
            </div>
            <i class="fa-solid fa-chevron-right step-separator"></i>
            <div class="step-item">
                <span class="step-num">2</span>
                <span>Enlace</span>
            </div>
            <i class="fa-solid fa-chevron-right step-separator"></i>
            <div class="step-item">
                <span class="step-num">3</span>
                <span>Nueva clave</span>
            </div>
        </div>

        <!-- Feedback Alert: Success -->
        @if(session('status'))
            <div class="modern-alert alert-success-modern">
                <i class="fa-solid fa-circle-check alert-icon"></i>
                <div>
                    <div class="fw-bold mb-1" style="font-size: 0.95rem;">¡Enlace de recuperación enviado!</div>
                    @if(session('status') == 'passwords.sent' || str_contains(strtolower(session('status')), 'emailed') || str_contains(strtolower(session('status')), 'enviado'))
                        Revisa la bandeja de entrada de tu correo (y también la carpeta de <strong>spam</strong> o correo no deseado). Haz clic en el enlace para crear tu nueva contraseña.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            </div>
        @endif

        <!-- Feedback Alert: Errors -->
        @if($errors->any())
            <div class="modern-alert alert-error-modern">
                <i class="fa-solid fa-circle-exclamation alert-icon"></i>
                <div>
                    <div class="fw-bold mb-1" style="font-size: 0.95rem;">Atención</div>
                    <div>
                        @if($errors->has('email'))
                            @php
                                $emailErr = $errors->first('email');
                            @endphp
                            @if(str_contains(strtolower($emailErr), 'user') || str_contains(strtolower($emailErr), 'usuario') || str_contains(strtolower($emailErr), 'find'))
                                No encontramos ningún usuario registrado con esa dirección de correo electrónico.
                            @elseif(str_contains(strtolower($emailErr), 'throttled') || str_contains(strtolower($emailErr), 'wait') || str_contains(strtolower($emailErr), 'espera'))
                                Por favor espera unos minutos antes de volver a solicitar un enlace.
                            @else
                                {{ $emailErr }}
                            @endif
                        @else
                            {{ $errors->first() }}
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Recovery Request Form -->
        <form method="POST" action="{{ route('password.email') }}" id="recoveryForm">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label-custom">
                    <i class="fa-solid fa-envelope text-primary"></i>
                    Correo electrónico asociado
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
                        placeholder="ejemplo@correo.com"
                        required
                        autofocus
                        autocomplete="email">
                </div>
            </div>

            <button type="submit" class="btn-submit-action" id="btnSubmit">
                <i class="fa-solid fa-paper-plane" id="btnIcon"></i>
                <span id="btnText">Enviar enlace de recuperación</span>
            </button>

            <!-- Back to Login Link -->
            <div class="back-link-wrapper">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Volver al inicio de sesión</span>
                </a>
            </div>

            <!-- Security Assurance -->
            <div class="security-badge">
                <i class="fa-solid fa-lock"></i>
                <span>Conexión protegida con cifrado TLS de 256 bits</span>
            </div>

        </form>

    </div>
</div>

<script>
    // Smooth loading indicator on form submit to prevent double-clicks
    const recoveryForm = document.getElementById('recoveryForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');

    if (recoveryForm && btnSubmit) {
        recoveryForm.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnIcon.className = 'fa-solid fa-circle-notch fa-spin';
            btnText.textContent = 'Enviando enlace seguro...';
        });
    }
</script>

</body>
</html>
