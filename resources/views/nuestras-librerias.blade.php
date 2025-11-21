<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replica Librerías Gonvill</title>
<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <style>
                body {
                    position: relative;
                    z-index: 0;
                }
                
                main .grid img {
                    position: relative;
                    z-index: 1 !important;
                }
                
                main .grid .absolute {
                    z-index: 10 !important;
                }
                
                .sticky {
                    z-index: 100 !important;
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
                <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
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
            <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Inicio</a></li>
            <li class="relative group">
                <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Libros ▾</a>
                <!-- Megamenú -->
                <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-2xl border border-gray-200 w-[800px] p-6 z-[150]">
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <h4 class="font-bold text-gray-800 mb-3">EXPLORAR</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Novedades</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-3">MATERIAS</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Libros para Todos</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Literatura</a></li>                                     
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Diccionario y Referencia</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Entretenimiento y Oficios</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Interés General</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Otros Idiomas</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Textos Escolares y Universitarios</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Sociales y Humanidades</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Arquitectura y Diseño</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Ingenierías y Computación</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Económicas Administrativas</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Derecho</a></li>
                            </ul>
                        </div>
                        <div>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias de la Salud</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Agronomía y Medio Ambiente</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Textos Escolares</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Otros Idiomas</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Infantiles</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Juveniles</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Gadgets / Accesorios</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Francés</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Inglés</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Chino</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Italiano</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Alemán</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Portugués</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-sky-500">Español para Extranjeros</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Impresión bajo demanda</a></li>
            <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Sobre Nosotros</a></li>
            <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Nuestras librerías</a></li>
            <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Bolsa de trabajo</a></li>
            <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Ayuda</a></li>
            <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >SchoolShop</a></li>
        </ul>
    </div>
</nav>

<!-- Sección Nuestras Librerías -->
<section class="max-w-[1200px] mx-auto px-3 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Nuestras librerías</h2>
    
    <div class="space-y-3">
        <!-- Guadalajara -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">GUADALAJARA, JAL.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Puerto Vallarta -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">PUERTO VALLARTA, JAL.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Chihuahua -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">CHIHUAHUA, CHIH.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Mazatlán -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">MAZATLÁN, SIN.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

      
        <!-- Culiacán -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">CULIACÁN, SIN.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Sucursal 1 -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="relative mb-4">
                            <img src="{{ asset('img/gonvill_culiacan.jpeg') }}" alt="Gonvill Culiacán Centro" class="w-full h-48 object-cover rounded-lg">
                            <div class="absolute bottom-2 right-2 bg-[#00A6CE] text-white p-2 rounded-full">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p class="font-semibold">Av. Álvaro Obregón #1686 Nte.</p>
                            <p>Esq. Blvd. Dr. Manuel Romero</p>
                            <p>Col. Gabriel Leyva, C.P. 80030</p>
                            <p class="mt-3 font-semibold">Horario:</p>
                            <p>Lunes a Sábado 9:00 a.m. a 7:00 p.m.</p>
                            <p>Domingo cerrado</p>
                            <div class="mt-4 space-y-1">
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#00A6CE]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    (667) 712-3109 - 712-3128
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#00A6CE]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    cln@gonvill.com.mx
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#00A6CE]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    (667) 712-29-97
                                </p>
                            </div>
                        </div>
                    </div>

            <!-- Sucursal 2 -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="relative mb-4">
                    <img src="{{ asset('img/gonvill_culiacan_ceiba.jpg') }}" alt="Gonvill Plaza La Ceiba" class="w-full h-48 object-cover rounded-lg">
                    <div class="absolute bottom-2 right-2 bg-[#00A6CE] text-white p-2 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                    <p class="font-semibold">Plaza La Ceiba Nivel 1 Local A11 Blvd.</p>
                    <p>Pedro Infante #3000 Pte.</p>
                    <p>Fracc. Jardines Tres Ríos</p>
                    <p>Col. Desarrollo Urbano, C.P. 80100</p>
                    <p class="mt-3 font-semibold">Horario:</p>
                    <p>Lunes a Viernes 10:00 a.m. a 7:00 p.m.</p>
                    <p>Sábado-Domingo 10:00 a.m. a 7:00 p.m.</p>
                    <div class="mt-4 space-y-1">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00A6CE]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            (667) 688-1730 - 688-1869
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00A6CE]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            plazalaceiba@gonvill.com.mx
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</details>

        <!-- CDMX -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">CDMX / OFICINA (NOTA: VENTA EXCLUSIVA MAYOREO).</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Monterrey -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">MONTERREY, N.L.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- León -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">LEÓN, GTO.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- San Luis Potosí -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">SAN LUIS POTOSÍ, S.L.P.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Torreón -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">TORREÓN, COAH.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Querétaro -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">QUERÉTARO, QRO.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>

        <!-- Aguascalientes -->
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                <span class="text-[#ed6190] font-semibold">AGUASCALIENTES, AGS.</span>
                <span class="text-[#ed6190] text-2xl">+</span>
            </summary>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <p class="text-gray-700">Información de sucursal disponible próximamente.</p>
            </div>
        </details>
    </div>
</section>

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
                    <img src="img/Paypal.png" alt="PayPal" class="h-10">
                    <img src="img/MP.png" alt="Mercado Pago" class="h-10">
                    <img src="img/AE.png" alt="American Express" class="h-10">
                    <img src="img/VISA.png" alt="Visa" class="h-10">
                    <img src="img/MC.png" alt="Mastercard" class="h-10">
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
</body>
</html>