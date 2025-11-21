<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Gonvill</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #ffa3c2 0%, #ff7eb8 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .menu-item {
            padding: 15px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid transparent;
        }

        .menu-item:hover {
            background: #fff5f9;
            border-color: #ffa3c2;
        }

        .menu-item.active {
            background: #fff5f9;
            border-color: #ffa3c2;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            width: 150px;
        }

        .info-value {
            color: #1a1a1a;
            flex: 1;
        }

        .btn-primary {
            background: #ffa3c2;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #ff7aa5;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: #ffa3c2;
            padding: 12px 24px;
            border-radius: 8px;
            border: 2px solid #ffa3c2;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #fff5f9;
        }

        .btn-danger {
            background: #f44336;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-warning {
            background: #fff3e0;
            color: #f57c00;
        }

        .badge-info {
            background: #e3f2fd;
            color: #1976d2;
        }
    </style>
</head>
<body class="bg-gray-50">

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
                <a href="#" class="hover:underline">Contacto</a>
                @if (Auth::check())
                    {{-- Si el usuario está autenticado, muestra su nombre --}}
                    <a href="{{ route('profile') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->first_name }}
                    </a>
                @else
                    {{-- Si no hay sesión iniciada, muestra "Mi cuenta" --}}
                    <a href="{{ route('mi.cuenta') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i>
                        Mi cuenta
                    </a>
                @endif
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

    <!-- Contenido Principal -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header del Perfil -->
        <div class="profile-header">
            <div class="flex items-center gap-6">
                <div class="bg-white text-[#ffa3c2] rounded-full w-24 h-24 flex items-center justify-center text-4xl font-bold">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
                    <p class="opacity-90">{{ Auth::user()->email }}</p>
                    <p class="text-sm opacity-75 mt-2">Miembro desde {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <!-- Menú Lateral -->
            <div class="col-span-3">
                <div class="profile-card">
                    <div class="menu-item active" data-section="info">
                        <i class="fas fa-user"></i>
                        <span>Mi Información</span>
                    </div>
                    <div class="menu-item" data-section="orders">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Mis Pedidos</span>
                    </div>
                    <div class="menu-item" data-section="favorites">
                        <i class="fas fa-heart"></i>
                        <span>Mis Favoritos</span>
                    </div>
                    <div class="menu-item" data-section="addresses">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Mis Direcciones</span>
                    </div>
                    <div class="menu-item" data-section="settings">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </div>
                    
                    <hr style="margin: 20px 0;">
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="menu-item w-full text-left" style="color: #f44336;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-span-9">
                <!-- Sección: Mi Información -->
                <div id="section-info" class="section-content">
                    <div class="profile-card">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Mi Información Personal</h2>
                            <button class="btn-secondary" onclick="toggleEdit()">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Nombre:</div>
                            <div class="info-value">{{ Auth::user()->first_name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Apellido:</div>
                            <div class="info-value">{{ Auth::user()->last_name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Teléfono:</div>
                            <div class="info-value">{{ Auth::user()->phone ?? 'No registrado' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Dirección:</div>
                            <div class="info-value">{{ Auth::user()->address ?? 'No registrada' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Miembro desde:</div>
                            <div class="info-value">{{ Auth::user()->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <div class="profile-card text-center">
                            <i class="fas fa-shopping-bag text-4xl text-[#ffa3c2] mb-3"></i>
                            <h3 class="text-3xl font-bold text-gray-800">0</h3>
                            <p class="text-gray-600">Pedidos Totales</p>
                        </div>
                        <div class="profile-card text-center">
                            <i class="fas fa-heart text-4xl text-[#ffa3c2] mb-3"></i>
                            <h3 class="text-3xl font-bold text-gray-800">0</h3>
                            <p class="text-gray-600">Favoritos</p>
                        </div>
                        <div class="profile-card text-center">
                            <i class="fas fa-star text-4xl text-[#ffa3c2] mb-3"></i>
                            <h3 class="text-3xl font-bold text-gray-800">0</h3>
                            <p class="text-gray-600">Reseñas</p>
                        </div>
                    </div>
                </div>

                <!-- Sección: Mis Pedidos -->
                <div id="section-orders" class="section-content" style="display: none;">
                    <div class="profile-card">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Pedidos</h2>
                        
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">No tienes pedidos aún</h3>
                            <p class="text-gray-500 mb-4">Explora nuestra tienda y realiza tu primera compra</p>
                            <a href="{{ route('inicio') }}" class="btn-primary inline-block">
                                <i class="fas fa-shopping-cart"></i> Ir a comprar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sección: Mis Favoritos -->
                <div id="section-favorites" class="section-content" style="display: none;">
                    <div class="profile-card">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Favoritos</h2>
                        
                        <div class="empty-state">
                            <i class="fas fa-heart"></i>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">No tienes favoritos guardados</h3>
                            <p class="text-gray-500 mb-4">Guarda tus libros favoritos para encontrarlos fácilmente</p>
                            <a href="{{ route('inicio') }}" class="btn-primary inline-block">
                                <i class="fas fa-book"></i> Explorar libros
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sección: Mis Direcciones -->
                <div id="section-addresses" class="section-content" style="display: none;">
                    <div class="profile-card">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Mis Direcciones</h2>
                            <button class="btn-primary">
                                <i class="fas fa-plus"></i> Agregar dirección
                            </button>
                        </div>
                        
                        <div class="empty-state">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">No tienes direcciones guardadas</h3>
                            <p class="text-gray-500">Agrega una dirección para facilitar tus compras</p>
                        </div>
                    </div>
                </div>

                <!-- Sección: Configuración -->
                <div id="section-settings" class="section-content" style="display: none;">
                    <div class="profile-card">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Configuración de Cuenta</h2>
                        
                        <div class="space-y-6">
                            <div class="border-b pb-4">
                                <h3 class="font-bold text-gray-800 mb-2">Cambiar Contraseña</h3>
                                <p class="text-gray-600 text-sm mb-3">Actualiza tu contraseña periódicamente para mantener tu cuenta segura</p>
                                <button class="btn-secondary">Cambiar contraseña</button>
                            </div>

                            <div class="border-b pb-4">
                                <h3 class="font-bold text-gray-800 mb-2">Notificaciones</h3>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" checked class="w-5 h-5">
                                        <span>Recibir ofertas y promociones por email</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" checked class="w-5 h-5">
                                        <span>Notificarme sobre mis pedidos</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" class="w-5 h-5">
                                        <span>Recibir recomendaciones personalizadas</span>
                                    </label>
                                </div>
                            </div>

                            <div class="border-b pb-4">
                                <h3 class="font-bold text-gray-800 mb-2 text-red-600">Zona Peligrosa</h3>
                                <p class="text-gray-600 text-sm mb-3">Esta acción es irreversible. Todos tus datos serán eliminados permanentemente.</p>
                                <button class="btn-danger">Eliminar mi cuenta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-4 gap-8 mb-8">
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

                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">POLÍTICAS DE LA TIENDA</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-sky-400">Aviso de privacidad</a></li>
                        <li><a href="#" class="hover:text-sky-400">Políticas de envío</a></li>
                    </ul>
                </div>

                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">SOPORTE AL CLIENTE</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Ayuda</a></li>
                        <li><a href="#" class="hover:text-sky-400">Contáctenos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Nuestras librerías</a></li>
                    </ul>
                </div>

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

            <div class="border-t border-gray-700 pt-8">
                <div class="text-center text-sm text-gray-400">
                    <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navegación entre secciones
        document.querySelectorAll('.menu-item[data-section]').forEach(item => {
            item.addEventListener('click', function() {
                // Remover active de todos
                document.querySelectorAll('.menu-item').forEach(mi => mi.classList.remove('active'));
                
                // Agregar active al clickeado
                this.classList.add('active');
                
                // Ocultar todas las secciones
                document.querySelectorAll('.section-content').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Mostrar la sección correspondiente
                const sectionId = 'section-' + this.getAttribute('data-section');
                document.getElementById(sectionId).style.display = 'block';
            });
        });

        function toggleEdit() {
            alert('Función de edición en desarrollo');
        }
    </script>

</body>
</html>