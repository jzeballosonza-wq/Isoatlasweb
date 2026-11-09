<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | Tienda ISOATLAS</title>

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
            padding: 28px 16px;
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

        .ambient-glow {
            position: fixed;
            width: 440px;
            height: 440px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.25) 0%, rgba(0,0,0,0) 70%);
            top: 8%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
        }

        .auth-container {
            width: 100%;
            max-width: 490px;
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
            background: linear-gradient(90deg, #7c3aed, #2563eb, #10b981);
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
        }

        /* Icon Badge */
        .icon-badge {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px auto;
            border-radius: 20px;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 30px;
            box-shadow: 
                0 14px 28px -6px rgba(124, 58, 237, 0.45),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
            position: relative;
            transition: transform 0.3s ease;
        }

        .icon-badge:hover {
            transform: scale(1.06) rotate(-4deg);
        }

        .icon-badge .badge-bubble {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 26px;
            height: 26px;
            background: #10b981;
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
            margin-bottom: 22px;
        }

        /* Input styling */
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
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
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
            color: #7c3aed;
            background: #f5f3ff;
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
            color: #475569;
        }

        /* Strength Meter */
        .strength-container {
            margin-top: 8px;
            margin-bottom: 16px;
        }

        .strength-bar-bg {
            height: 6px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 6px;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0%;
            border-radius: 999px;
            transition: all 0.3s ease;
            background: #cbd5e1;
        }

        .strength-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.76rem;
            color: #64748b;
        }

        .strength-text {
            font-weight: 700;
        }

        /* Requirement Checklist */
        .checklist-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 0.78rem;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .check-item.valid {
            color: #059669;
            font-weight: 600;
        }

        .check-item i {
            font-size: 0.85rem;
        }

        /* Match indicator */
        .match-feedback {
            font-size: 0.78rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .match-feedback.matched {
            color: #059669;
        }

        .match-feedback.unmatched {
            color: #d97706;
        }

        /* Submit Button */
        .btn-submit-action {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 50%, #1d4ed8 100%);
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
            box-shadow: 0 10px 22px -5px rgba(124, 58, 237, 0.45);
            transition: all 0.25s ease;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -5px rgba(124, 58, 237, 0.55);
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

        /* Back Link */
        .back-link-wrapper {
            margin-top: 22px;
            padding-top: 18px;
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

        .back-link:hover {
            color: #2563eb;
        }

        /* Modern Alert */
        .modern-alert {
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            font-size: 0.88rem;
            line-height: 1.45;
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
                <span class="text-primary fw-semibold"><i class="fa-solid fa-lock me-1"></i>Seguridad</span>
            </div>
        </div>

        <!-- Central Lock icon -->
        <div class="icon-badge">
            <i class="fa-solid fa-lock-open"></i>
            <div class="badge-bubble">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <!-- Heading -->
        <div class="text-center">
            <h1 class="title-text">Crear nueva contraseña</h1>
            <p class="subtitle-text">
                Define una nueva contraseña segura para recuperar el acceso a tu cuenta.
            </p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="modern-alert alert-error-modern">
                <i class="fa-solid fa-circle-exclamation alert-icon"></i>
                <div>
                    <div class="fw-bold mb-1" style="font-size: 0.95rem;">Hubo un problema</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            @if(str_contains(strtolower($error), 'token'))
                                <li>Este enlace de recuperación ha caducado o no es válido. Solicita uno nuevo.</li>
                            @elseif(str_contains(strtolower($error), 'confirmation') || str_contains(strtolower($error), 'match'))
                                <li>Las contraseñas no coinciden.</li>
                            @elseif(str_contains(strtolower($error), 'least 8'))
                                <li>La contraseña debe tener al menos 8 caracteres.</li>
                            @else
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" id="resetForm">
            @csrf

            <!-- Hidden Token from Route -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
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
                        value="{{ old('email', $request->email) }}"
                        required
                        autocomplete="email"
                        readonly
                        style="background: #f8fafc; color: #475569;">
                </div>
            </div>

            <!-- New Password -->
            <div class="mb-2">
                <label for="password" class="form-label-custom">
                    <i class="fa-solid fa-key text-primary"></i>
                    Nueva contraseña
                </label>
                <div class="input-group-custom">
                    <span class="input-icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Mínimo 8 caracteres"
                        required
                        autocomplete="new-password">
                    <button type="button" class="btn-toggle-eye" id="togglePassword" title="Mostrar/ocultar contraseña">
                        <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>
            </div>

            <!-- Password Strength Bar -->
            <div class="strength-container">
                <div class="strength-bar-bg">
                    <div class="strength-bar-fill" id="strengthBar"></div>
                </div>
                <div class="strength-meta">
                    <span>Seguridad de la clave:</span>
                    <span class="strength-text" id="strengthText">Sin ingresar</span>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label-custom">
                    <i class="fa-solid fa-shield-halved text-primary"></i>
                    Confirmar nueva contraseña
                </label>
                <div class="input-group-custom">
                    <span class="input-icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Repite la contraseña"
                        required
                        autocomplete="new-password">
                    <button type="button" class="btn-toggle-eye" id="toggleConfirm" title="Mostrar/ocultar contraseña">
                        <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
                <div id="matchFeedback" class="match-feedback d-none"></div>
            </div>

            <!-- Requirements Checklist -->
            <div class="checklist-box">
                <div class="check-item" id="checkLength">
                    <i class="fa-regular fa-circle" id="iconLength"></i>
                    <span>Mínimo 8 caracteres</span>
                </div>
                <div class="check-item" id="checkMatch">
                    <i class="fa-regular fa-circle" id="iconMatch"></i>
                    <span>Ambas contraseñas coinciden</span>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit-action" id="btnSubmit">
                <i class="fa-solid fa-circle-check" id="btnIcon"></i>
                <span id="btnText">Guardar nueva contraseña</span>
            </button>

            <!-- Back to Login -->
            <div class="back-link-wrapper">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Cancelar y volver a iniciar sesión</span>
                </a>
            </div>

        </form>

    </div>
</div>

<script>
    // Eye toggles
    function setupToggle(buttonId, inputId, iconId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (btn && input && icon) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fa-regular fa-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'fa-regular fa-eye';
                }
            });
        }
    }

    setupToggle('togglePassword', 'password', 'eyeIcon1');
    setupToggle('toggleConfirm', 'password_confirmation', 'eyeIcon2');

    // Real-time password strength and match evaluation
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const checkLength = document.getElementById('checkLength');
    const iconLength = document.getElementById('iconLength');
    const checkMatch = document.getElementById('checkMatch');
    const iconMatch = document.getElementById('iconMatch');
    const matchFeedback = document.getElementById('matchFeedback');

    function calculateStrength(pwd) {
        let score = 0;
        if (!pwd) return { score: 0, text: 'Sin ingresar', color: '#cbd5e1', percent: 0 };

        if (pwd.length >= 8) score += 25;
        if (pwd.length >= 12) score += 15;
        if (/[A-Z]/.test(pwd)) score += 20;
        if (/[0-9]/.test(pwd)) score += 20;
        if (/[^A-Za-z0-9]/.test(pwd)) score += 20;

        if (score < 30) {
            return { score, text: 'Muy débil', color: '#ef4444', percent: 25 };
        } else if (score < 60) {
            return { score, text: 'Regular', color: '#f59e0b', percent: 50 };
        } else if (score < 80) {
            return { score, text: 'Buena', color: '#3b82f6', percent: 75 };
        } else {
            return { score, text: 'Excelente', color: '#10b981', percent: 100 };
        }
    }

    function updateFormFeedback() {
        const pwd = passwordInput.value;
        const confirm = confirmInput.value;

        // Strength
        const strength = calculateStrength(pwd);
        strengthBar.style.width = strength.percent + '%';
        strengthBar.style.backgroundColor = strength.color;
        strengthText.textContent = strength.text;
        strengthText.style.color = strength.color;

        // Check Length
        if (pwd.length >= 8) {
            checkLength.classList.add('valid');
            iconLength.className = 'fa-solid fa-circle-check text-success';
        } else {
            checkLength.classList.remove('valid');
            iconLength.className = 'fa-regular fa-circle';
        }

        // Check Match
        if (confirm.length > 0) {
            matchFeedback.classList.remove('d-none');
            if (pwd === confirm) {
                checkMatch.classList.add('valid');
                iconMatch.className = 'fa-solid fa-circle-check text-success';
                matchFeedback.className = 'match-feedback matched';
                matchFeedback.innerHTML = '<i class="fa-solid fa-check"></i> Las contraseñas coinciden';
            } else {
                checkMatch.classList.remove('valid');
                iconMatch.className = 'fa-regular fa-circle';
                matchFeedback.className = 'match-feedback unmatched';
                matchFeedback.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Las contraseñas no coinciden aún';
            }
        } else {
            checkMatch.classList.remove('valid');
            iconMatch.className = 'fa-regular fa-circle';
            matchFeedback.classList.add('d-none');
        }
    }

    if (passwordInput && confirmInput) {
        passwordInput.addEventListener('input', updateFormFeedback);
        confirmInput.addEventListener('input', updateFormFeedback);
    }

    // Submit loading state
    const resetForm = document.getElementById('resetForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');

    if (resetForm && btnSubmit) {
        resetForm.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnIcon.className = 'fa-solid fa-circle-notch fa-spin';
            btnText.textContent = 'Actualizando contraseña...';
        });
    }
</script>

</body>
</html>
