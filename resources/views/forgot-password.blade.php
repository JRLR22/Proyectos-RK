<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Gonvill</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .form-container {
            min-height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-card {
            width: 480px;
            padding: 40px;
            border: 2px solid #ffa3c2;
            border-radius: 15px;
            background: white;
            box-shadow: 0 0 20px rgba(198, 0, 138, 0.2);
        }

        .form-card h2 {
            font-size: 24px;
            font-weight: bold;
            color: #ffa3c2;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-card p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            outline: none;
            transition: .2s;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #ffa3c2;
            box-shadow: 0 0 4px rgba(255, 163, 194, 0.5);
        }

        .btn-auth {
            width: 100%;
            padding: 12px;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: .3s;
            background: #ffa3c2;
        }

        .btn-auth:hover {
            transform: scale(1.03);
            background: #ff7aa5;
        }

        .btn-auth:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            transition: .3s;
        }

        .step-dot.active {
            background: #ffa3c2;
        }

        .icon-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .icon-container i {
            font-size: 50px;
            color: #ffa3c2;
        }

        .password-box {
            position: relative;
            margin-bottom: 15px;
        }

        .password-box input {
            margin-bottom: 0;
            padding-right: 45px;
        }

        .password-box .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #ffa3c2;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        #tokenInput {
            font-family: monospace;
            font-size: 12px;
            resize: vertical;
            min-height: 80px;
        }
    </style>
</head>
<body class="font-roboto text-gray-800 bg-gray-50">

    <!-- Header Simplificado -->
    <div class="bg-[#ffa3c2] text-white py-3">
        <div class="container mx-auto px-4">
            <a href="{{ route('inicio') }}" class="flex items-center gap-2 hover:opacity-80">
                <i class="fas fa-arrow-left"></i>
                <span>Volver a Gonvill</span>
            </a>
        </div>
    </div>

    <!-- Formulario de Recuperación -->
    <div class="form-container">
        <div class="form-card">
            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step-dot" id="step1-dot"></div>
                <div class="step-dot" id="step2-dot"></div>
                <div class="step-dot" id="step3-dot"></div>
            </div>

            <!-- Mensajes de error/éxito -->
            <div id="alertContainer"></div>

            <!-- PASO 1: Ingresar email -->
            <div id="step1" style="display: block;">
                <div class="icon-container">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2>¿Olvidaste tu contraseña?</h2>
                <p>No te preocupes, te enviaremos un código para restablecerla</p>

                <form id="forgotForm">
                    <input type="email" id="emailInput" placeholder="Correo electrónico" class="form-control" required>
                    <button type="submit" class="btn-auth" id="sendBtn">
                        <span id="sendBtnText">Enviar código</span>
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('mi.cuenta') }}">← Volver al inicio de sesión</a>
                </div>
            </div>

            <!-- PASO 2: Verificar código -->
            <div id="step2" style="display: none;">
                <div class="icon-container">
                    <i class="fas fa-key"></i>
                </div>
                <h2>Revisa tu correo</h2>
                <p>Hemos enviado un código de 60 caracteres a <strong id="emailDisplay"></strong></p>

                <form id="verifyForm">
                    <textarea id="tokenInput" placeholder="Pega aquí el código de verificación" class="form-control" required></textarea>
                    <button type="submit" class="btn-auth" id="verifyBtn">
                        <span id="verifyBtnText">Verificar código</span>
                    </button>
                </form>

                <div class="back-link">
                    <a href="#" onclick="goToStep(1); return false;">← Volver a ingresar email</a>
                </div>
            </div>

            <!-- PASO 3: Nueva contraseña -->
            <div id="step3" style="display: none;">
                <div class="icon-container">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Nueva contraseña</h2>
                <p>Elige una contraseña segura</p>

                <form id="resetForm">
                    <div class="password-box">
                        <input type="password" id="passwordInput" placeholder="Nueva contraseña (mínimo 6 caracteres)" class="form-control" required>
                        <span class="toggle-pass" onclick="togglePassword('passwordInput', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="password-box">
                        <input type="password" id="confirmPasswordInput" placeholder="Confirmar contraseña" class="form-control" required>
                        <span class="toggle-pass" onclick="togglePassword('confirmPasswordInput', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn-auth" id="resetBtn">
                        <span id="resetBtnText">Cambiar contraseña</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let userEmail = '';
        let resetToken = '';

        // Actualizar indicador de pasos
        function updateStepIndicator() {
            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById(`step${i}-dot`);
                dot.classList.toggle('active', i <= currentStep);
            }
        }

        // Cambiar de paso
        function goToStep(step) {
            // Ocultar todos los pasos
            for (let i = 1; i <= 3; i++) {
                document.getElementById(`step${i}`).style.display = 'none';
            }
            // Mostrar paso actual
            document.getElementById(`step${step}`).style.display = 'block';
            currentStep = step;
            updateStepIndicator();
        }

        // Mostrar alertas
        function showAlert(message, type = 'success') {
            const container = document.getElementById('alertContainer');
            container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => container.innerHTML = '', 5000);
        }

        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const i = icon.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                i.classList.remove('fa-eye');
                i.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                i.classList.remove('fa-eye-slash');
                i.classList.add('fa-eye');
            }
        }

        // PASO 1: Enviar email
        document.getElementById('forgotForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('emailInput').value.trim();
            const btn = document.getElementById('sendBtn');
            const btnText = document.getElementById('sendBtnText');

            if (!email) {
                showAlert('Por favor ingresa tu correo electrónico', 'error');
                return;
            }

            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Correo electrónico inválido', 'error');
                return;
            }

            btn.disabled = true;
            btnText.textContent = 'Enviando...';

            try {
                const response = await fetch('/api/password/forgot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (response.ok) {
                    userEmail = email;
                    document.getElementById('emailDisplay').textContent = email;
                    goToStep(2);
                    showAlert('✅ Correo enviado. Revisa tu bandeja de entrada.', 'success');
                } else {
                    showAlert(data.message || 'No se pudo enviar el correo', 'error');
                }
            } catch (error) {
                showAlert('Error de conexión con el servidor', 'error');
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Enviar código';
            }
        });

        // PASO 2: Verificar token
        document.getElementById('verifyForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const token = document.getElementById('tokenInput').value.trim();
            const btn = document.getElementById('verifyBtn');
            const btnText = document.getElementById('verifyBtnText');

            if (!token) {
                showAlert('Por favor ingresa el código que recibiste', 'error');
                return;
            }

            btn.disabled = true;
            btnText.textContent = 'Verificando...';

            try {
                const response = await fetch('/api/password/verify-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: userEmail, token })
                });

                const data = await response.json();

                if (response.ok) {
                    resetToken = token;
                    goToStep(3);
                    showAlert('✅ Código verificado correctamente', 'success');
                } else {
                    showAlert(data.message || 'Código inválido o expirado', 'error');
                }
            } catch (error) {
                showAlert('Error de conexión con el servidor', 'error');
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Verificar código';
            }
        });

        // PASO 3: Cambiar contraseña
        document.getElementById('resetForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('passwordInput').value;
            const confirmPassword = document.getElementById('confirmPasswordInput').value;
            const btn = document.getElementById('resetBtn');
            const btnText = document.getElementById('resetBtnText');

            if (!password || !confirmPassword) {
                showAlert('Por favor completa todos los campos', 'error');
                return;
            }

            if (password.length < 6) {
                showAlert('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }

            if (password !== confirmPassword) {
                showAlert('Las contraseñas no coinciden', 'error');
                return;
            }

            btn.disabled = true;
            btnText.textContent = 'Guardando...';

            try {
                const response = await fetch('/api/password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        token: resetToken,
                        password: password,
                        password_confirmation: confirmPassword
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert('🎉 ¡Contraseña actualizada correctamente!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("mi.cuenta") }}';
                    }, 2000);
                } else {
                    showAlert(data.message || 'No se pudo cambiar la contraseña', 'error');
                    btn.disabled = false;
                    btnText.textContent = 'Cambiar contraseña';
                }
            } catch (error) {
                showAlert('Error de conexión con el servidor', 'error');
                btn.disabled = false;
                btnText.textContent = 'Cambiar contraseña';
            }
        });

        // Inicializar
        updateStepIndicator();
    </script>
</body>
</html>