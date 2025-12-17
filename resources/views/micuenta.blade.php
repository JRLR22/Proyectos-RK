<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Gonvill</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Estilos de formularios */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 30px;
            padding: 40px 20px;
        }

        .form-card {
            width: 380px;
            padding: 30px;
            border: 2px solid #ffa3c2;
            border-radius: 15px;
            background: white;
            box-shadow: 0 0 10px rgba(198, 0, 138, 0.2);
        }

        .form-card h2 {
            font-size: 20px;
            font-weight: bold;
            color: #ffa3c2;
            text-align: center;
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 12px;
            outline: none;
            transition: .2s;
        }

        .form-control:focus {
            border-color: #ffa3c2;
            box-shadow: 0 0 4px rgba(255, 163, 194, 0.5);
        }

        .form-control.error {
            border-color: #F44336;
        }

        .btn-auth {
            width: 100%;
            padding: 10px;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: .3s;
            background: #ffa3c2;
        }

        .btn-auth:hover {
            transform: scale(1.03);
            background: #ff7aa5;
        }

        .password-box {
            position: relative;
            margin-bottom: 12px;
            width: 100%;
        }

        .password-box input.form-control {
            margin-bottom: 0;
        }

        .password-box .toggle-pass {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .password-box svg {
            stroke: #555;
            fill: #555;
            transition: 0.2s;
        }

        .password-box svg:hover {
            stroke: #ff7eb8;
            fill: #ff7eb8;
        }

        .error-message {
            color: #F44336;
            font-size: 12px;
            margin-top: -8px;
            margin-bottom: 8px;
            display: block;
        }

        .success-message {
            background: #4CAF50;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media(max-width: 768px) {
            .form-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body class="font-roboto text-gray-800 bg-white">

    <!-- Header Superior -->
    <div class="bg-[#ffa3c2] text-white py-2 sticky top-0 z-[100]">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-3">
                <a href="#" class="bg-blue-700 rounded-full w-8 h-8 flex items-center justify-center hover:bg-blue-800">
                    <i class="fab fa-facebook-f text-sm"></i>
                </a>
                <a href="#" class="bg-sky-400 rounded-full w-8 h-8 flex items-center justify-center hover:bg-sky-500">
                    <i class="fab fa-twitter text-sm"></i>
                </a>
                <a href="#" class="bg-pink-600 rounded-full w-8 h-8 flex items-center justify-center hover:bg-pink-700">
                    <i class="fab fa-instagram text-sm"></i>
                </a>
                <a href="#" class="bg-red-600 rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
                    <i class="fab fa-youtube text-sm"></i>
                </a>
            </div>
            <div class="flex gap-4 items-center">
                <a href="{{ route('contacto') }}" class="hover:underline">Contacto</a>
                @auth
                    <a href="{{ route('profile') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i> {{ Auth::user()->first_name }}
                    </a>
                @else
                    <a href="{{ route('mi.cuenta') }}" class="flex items-center gap-1 hover:underline">
                        <i class="far fa-user"></i> Mi cuenta
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Header Principal -->
    <div class="bg-white shadow-md py-4 sticky top-10 z-[99]">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Logo -->
                <div>
                    <a href="{{ route('inicio') }}">
                        <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
                    </a>
                </div>
                <!-- Buscador -->
                <div class="flex-1 max-w-2xl">
                    <div class="flex">
                        <input type="text" placeholder="Título, Autor, ISBN, Código Gonvill" 
                               class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-sky-500">
                        <button class="bg-[#ffa3c2] hover:bg-[#FF82AE] text-white px-6">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <a href="#" class="text-sm text-gray-600 mt-1 inline-block">› Búsqueda avanzada</a>
                </div>

                <!-- Carrito -->
                <div class="flex items-center gap-4">
                    <a href="#" class="relative">
                        <i class="far fa-heart text-2xl text-gray-700"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>
                    <a href="#" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-2xl text-gray-800"></i>
                        </div>
                        <span class="text-gray-700">Mi compra</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menú de Navegación -->
    <nav class="bg-white border-t border-b border-gray-200 sticky top-32 z-[98]">
        <div class="container mx-auto px-4">
            <ul class="flex gap-8 py-4 justify-center">
                <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Inicio</a></li>
                <li><a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a></li>
                <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Impresión bajo demanda</a></li>
                <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Sobre Nosotros</a></li>
                <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Nuestras librerías</a></li>
                <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Bolsa de trabajo</a></li>
                <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Ayuda</a></li>
                <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">SchoolShop</a></li>
            </ul>
        </div>
    </nav>

    <!-- Cuerpo de la web vista -->
    <div class="form-container">
        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="success-message" style="position: fixed; top: 20px; right: 20px; z-index: 1000; max-width: 400px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- REGISTRO -->
        <div class="form-card">
            <h3 style="color:#ffa3c2; margin-bottom:20px; font-weight:700; font-family:'Poppins', sans-serif; font-size:20px;">Regístrate</h3>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <label>Nombre</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Nombre" class="form-control @error('first_name') error @enderror" required>
                @error('first_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label>Apellido</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Apellido" class="form-control @error('last_name') error @enderror" required>
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label>E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" class="form-control @error('email') error @enderror" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label>Contraseña</label>
                <div class="password-box">
                    <input type="password" name="password" id="regPassword" placeholder="Contraseña (mínimo 6 caracteres)" class="form-control @error('password') error @enderror" required>
                    <span class="toggle-pass" data-input="regPassword">
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                        </svg>
                        <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M2 12s3 7 10 7c1.8 0 3.3-.4 4.6-1l3.1 3.1 1.4-1.4L3.4 3.4 2 4.8l3.3 3.3C3.4 9.3 2 12 2 12zm8-3.9l1.7 1.7a2 2 0 0 0-1.7 2.2l1.7 1.7a2 2 0 0 0 2.2-1.7l1.7 1.7a4 4 0 0 1-6-5.6z"/>
                        </svg>
                    </span>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <label>Confirmar contraseña</label>
                <div class="password-box">
                    <input type="password" name="password_confirmation" id="regPasswordConfirm" placeholder="Confirmar contraseña" class="form-control" required>
                    <span class="toggle-pass" data-input="regPasswordConfirm">
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                        </svg>
                        <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M2 12s3 7 10 7c1.8 0 3.3-.4 4.6-1l3.1 3.1 1.4-1.4L3.4 3.4 2 4.8l3.3 3.3C3.4 9.3 2 12 2 12zm8-3.9l1.7 1.7a2 2 0 0 0-1.7 2.2l1.7 1.7a2 2 0 0 0 2.2-1.7l1.7 1.7a4 4 0 0 1-6-5.6z"/>
                        </svg>
                    </span>
                </div>

                <label>Número Celular (opcional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Teléfono" class="form-control">

                <label>Dirección (opcional)</label>
                <textarea name="address" placeholder="Dirección" class="form-control" rows="2">{{ old('address') }}</textarea>

                <div style="margin-top:10px; display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" required>
                    <small>He leído y acepto la <a href="{{ route('proteccion-datos') }}" style="color:#ffa3c2;">política de privacidad</a>.</small>
                </div>

                <button type="submit" class="btn-auth" style="margin-top:15px;">Registrarse</button>
            </form>
        </div>

        <!-- LOGIN -->
        <div class="form-card">
            <h3 style="color:#ffa3c2; margin-bottom:20px; font-weight:700; font-family:'Poppins', sans-serif; font-size:20px;">Iniciar Sesión</h3>

           <form method="POST" action="{{ route('login') }}">
            @csrf
                <label>E-mail</label>
                <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Correo electrónico" required>
                <span class="error-message" id="emailError" style="display:none;"></span>

                <label>Contraseña</label>
                <div class="password-box">
                    <input type="password" name="password" id="loginPasswordInput" placeholder="Contraseña" class="form-control" required>
                    <span class="toggle-pass" data-input="loginPasswordInput">
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                        </svg>
                        <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                            <path d="M2 12s3 7 10 7c1.8 0 3.3-.4 4.6-1l3.1 3.1 1.4-1.4L3.4 3.4 2 4.8l3.3 3.3C3.4 9.3 2 12 2 12zm8-3.9l1.7 1.7a2 2 0 0 0-1.7 2.2l1.7 1.7a2 2 0 0 0 2.2-1.7l1.7 1.7a4 4 0 0 1-6-5.6z"/>
                        </svg>
                    </span>
                </div>
                <span class="error-message" id="passwordError" style="display:none;"></span>

                <div style="text-align: right; margin-bottom: 15px;">
                    <a href="#" style="color:#ffa3c2; font-size:13px;">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-auth">Entrar</button>
            </form>
        </div>
    </div>

      <!-- Newsletter -->
    <div class="bg-[#ffa3c2] py-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 text-white">
                    <i class="far fa-envelope text-5xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Boletín de Novedades</h3>
                        <p class="text-sm">Suscríbete y estarás al tanto de nuestras novedades</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <input type="email" placeholder="Email" class="px-4 py-2 w-80 rounded focus:outline-none">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-950">Suscribir</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-4 gap-8 mb-8">
                <!-- Servicio al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">SERVICIO AL CLIENTE</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Mis Pedidos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Favoritos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Direcciones</a></li>
                    </ul>
                </div>

                <!-- Políticas de la Tienda -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">POLÍTICAS DE LA TIENDA</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-sky-400">Aviso de privacidad</a></li>
                        <li><a href="#" class="hover:text-sky-400">Políticas de envío</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de venta en línea</a></li>
                    </ul>
                </div>

                <!-- Soporte al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">SOPORTE AL CLIENTE</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Ayuda</a></li>
                        <li><a href="#" class="hover:text-sky-400">Contáctenos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Nuestras librerías</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de dinero electrónico</a></li>
                    </ul>
                </div>

                <!-- Facturación -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">FACTURACIÓN</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Facturación electrónica</a></li>
                    </ul>
                </div>
            </div>

            <!-- Métodos de Pago -->
            <div class="border-t border-gray-700 pt-8">
                <div class="flex justify-center items-center gap-6 mb-6">
                    <img src="{{ asset('img/Paypal.png') }}" alt="PayPal" class="h-10">
                    <img src="{{ asset('img/MP.png') }}" alt="Mercado Pago" class="h-10">
                    <img src="{{ asset('img/AE.png') }}" alt="American Express" class="h-10">
                    <img src="{{ asset('img/VISA.png') }}" alt="Visa" class="h-10">
                    <img src="{{ asset('img/MC.png') }}" alt="Mastercard" class="h-10">
                </div>

                <!-- Copyright -->
                <div class="text-center text-sm text-gray-400 space-y-2">
                    <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                    <p>Los precios y la disponibilidad de los productos están sujetos a cambio sin previo aviso y solo se aplican para ventas en línea.</p>
                    <p>Los precios mostrados incluyen IVA.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-pass').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const input = document.getElementById(this.getAttribute('data-input'));
                const eyeOpen = this.querySelector('.eye-open');
                const eyeClosed = this.querySelector('.eye-closed');

                if (input.type === "password") {
                    input.type = "text";
                    eyeOpen.style.display = "none";
                    eyeClosed.style.display = "block";
                } else {
                    input.type = "password";
                    eyeClosed.style.display = "none";
                    eyeOpen.style.display = "block";
                }
            });
        });

        // Auto-hide success message after 5 seconds
        const successMsg = document.querySelector('.success-message');
        if (successMsg) {
            setTimeout(() => {
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 300);
            }, 5000);
        }

    </script>
 


 
</body>
</html>