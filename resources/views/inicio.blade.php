<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Replica Librerías Gonvill</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    
    /* Ocultar scrollbar en carrusel pero mantener funcionalidad */
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>
<body class="bg-gray-50">
    <!-- Header Superior -->
    <div class="bg-[#ffa3c2] text-white py-2 sticky top-0 z-[100]">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-2 md:gap-3">
                <a href="#" class="bg-blue-700 rounded-full w-7 h-7 md:w-8 md:h-8 flex items-center justify-center hover:bg-blue-800">
                    <i class="fab fa-facebook-f text-xs md:text-sm"></i>
                </a>
                <a href="#" class="bg-sky-400 rounded-full w-7 h-7 md:w-8 md:h-8 flex items-center justify-center hover:bg-sky-500">
                    <i class="fab fa-twitter text-xs md:text-sm"></i>
                </a>
                <a href="#" class="bg-pink-600 rounded-full w-7 h-7 md:w-8 md:h-8 flex items-center justify-center hover:bg-pink-700">
                    <i class="fab fa-instagram text-xs md:text-sm"></i>
                </a>
                <a href="#" class="bg-red-600 rounded-full w-7 h-7 md:w-8 md:h-8 flex items-center justify-center hover:bg-red-700">
                    <i class="fab fa-youtube text-xs md:text-sm"></i>
                </a>
            </div>
             <div class="flex gap-4 items-center">
                <a href="#" class="hover:underline">Contacto</a>
                @if (Auth::check())
                   <!-- Si el usuario está autenticado, muestra su nombre -->
                    <a href="{{ route('profile') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->first_name }}
                    </a>
                @else
                    <!-- Si no hay sesión iniciada, muestra "Mi cuenta" -->
                    <a href="{{ route('mi.cuenta') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i>
                        Mi cuenta
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- Header Principal -->
    <div class="bg-white shadow-md py-3 md:py-4 sticky top-11 md:top-10 z-[99]">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between gap-2 md:gap-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <img src="img/logo_Gonvill_pink.png" alt="Gonvill" class="h-12 md:h-16 lg:h-20">
                </div>

                <!-- Buscador - Oculto en móvil -->
                <div class="hidden md:flex flex-1 max-w-2xl">
                    <div class="flex-1">
                        <div class="flex">
                            <input type="text" placeholder="Título, Autor, ISBN, Código Gonvill" 
                                   class="w-full px-4 py-2 md:py-3 border border-gray-300 focus:outline-none focus:border-sky-500 text-sm">
                            <button class="bg-[#ffa3c2] hover:bg-[#FF82AE] text-white px-4 md:px-6">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="#" class="text-xs text-gray-600 mt-1 inline-block">› Búsqueda avanzada</a>
                    </div>
                </div>


                <!-- Carrito e iconos -->
                <div class="flex items-center gap-2 md:gap-4">
                    <a href="{{ route('wishlist.index') }}" class="relative hidden sm:block">
                        <i class="far fa-heart text-xl md:text-2xl text-gray-700 hover:text-red-500"></i>
                        <span class="absolute -top-3 -left-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 md:w-5 md:h-5 flex items-center justify-center">
                         {{ Auth::check() ? Auth::user()->wishlist()->count() : 0 }}
                        </span>
                    </a>

                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-xl md:text-2xl text-gray-700 hover:text-red-500"></i>
                            <span class="absolute -top-4 -left-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 md:w-5 md:h-5 flex items-center justify-center">
                                @auth
                                    @php
                                        $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
                                        $itemsCount = $cart ? $cart->items()->sum('quantity') : 0;
                                    @endphp
                                    {{ $itemsCount }}
                                @else
                                    0
                                @endauth
                            </span>
                        <span class="hidden sm:inline text-gray-700 text-sm">Mi compra</span>
                    </a>
                </div>

            <button class="md:hidden text-2xl text-gray-700">
                <i class="fas fa-user"></i>
            </button>

                <!-- Menú Hamburger - Solo móvil -->
                <button id="mobile-menu-btn" class="md:hidden text-2xl text-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>



            <!-- Buscador móvil - debajo del header en móvil -->
            <div class="md:hidden mt-3">
                <div class="flex">
                    <input type="text" placeholder="Buscar..." 
                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-sky-500 text-sm rounded-l">
                    <button class="bg-[#ffa3c2] hover:bg-[#FF82AE] text-white px-4 rounded-r">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menú de Navegación - Desktop -->
    <nav class="bg-white border-t border-b border-gray-200 sticky top-[88px] md:top-32 z-[98] hidden md:block">
        <div class="container mx-auto px-4">
            <ul class="flex gap-4 lg:gap-8 py-4 justify-center flex-wrap text-sm lg:text-base">
                <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Inicio</a></li>
                <li class="relative group">
                    <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a>
                    <!-- Megamenú -->
                    <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-2xl border border-gray-200 w-[600px] lg:w-[800px] p-6 z-[150]">
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3">EXPLORAR</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="{{ route('new.releases') }}" class="text-gray-600 hover:text-sky-500">Novedades</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3">MATERIAS</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Literatura</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Arte</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Autoayuda</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Sociales</a></li>
                                </ul>
                            </div>
                            <div class="hidden lg:block">
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Infantiles</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Juveniles</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Idiomas</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Impresión bajo demanda</a></li>
                <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Sobre Nosotros</a></li>
                <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Nuestras librerías</a></li>
                <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1 hidden lg:inline">Bolsa de trabajo</a></li>
                <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1 hidden xl:inline">Ayuda</a></li>
                <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1 hidden xl:inline">SchoolShop</a></li>
            </ul>
        </div>
    </nav>

            <!-- Menú móvil desplegable debajo del buscador -->
            <nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-b">
                <ul class="flex flex-col py-4 text-sm">
                    <li><a href="{{ route('inicio') }}" class="block px-4 py-2 text-[#DB7B9E]">Inicio</a></li>

                    <li>
                        <button id="libros-toggle" class="w-full text-left px-4 py-2 text-[#DB7B9E] flex justify-between items-center">
                            Libros
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <ul id="libros-submenu" class="hidden pl-8 space-y-2 pb-2 text-gray-600">
                            <li><a href="{{ route('new.releases') }}" class="block py-1">Novedades</a></li>
                            <li><a href="#" class="block py-1">Literatura</a></li>
                            <li><a href="#" class="block py-1">Arte</a></li>
                            <li><a href="#" class="block py-1">Infantiles</a></li>
                            <li><a href="#" class="block py-1">Juveniles</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('impresion.demanda') }}" class="block px-4 py-2 text-[#DB7B9E]">Impresión bajo demanda</a></li>
                    <li><a href="{{ route('sobre.nosotros') }}" class="block px-4 py-2 text-[#DB7B9E]">Sobre Nosotros</a></li>
                    <li><a href="{{ route('nuestras.librerias') }}" class="block px-4 py-2 text-[#DB7B9E]">Nuestras librerías</a></li>
                    <li><a href="{{ route('bolsa.trabajo') }}" class="block px-4 py-2 text-[#DB7B9E]">Bolsa de trabajo</a></li>
                    <li><a href="{{ route('ayuda') }}" class="block px-4 py-2 text-[#DB7B9E]">Ayuda</a></li>
                    <li><a href="{{ route('schoolshop') }}" class="block px-4 py-2 text-[#DB7B9E]">SchoolShop</a></li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- HERO / BANNER con carrusel -->
    <main class="max-w-[1200px] mx-auto px-3 py-4">
        <div id="carousel" class="relative rounded overflow-hidden h-[200px] sm:h-[280px] md:h-[350px] lg:h-[420px]">
           <div class="carousel-inner absolute inset-0">
            <div class="w-full h-full absolute transition-opacity duration-700 opacity-100 flex items-center justify-center text-white text-2xl">
                <img src="{{ asset('img/carrusel_1.jpg') }}" alt="carrusel.1">
            </div>
            <div class="w-full h-full absolute transition-opacity duration-700 opacity-0 flex items-center justify-center text-white text-2xl">
                <img src="{{ asset('img/carrusel_2.png') }}" alt="carrusel.2">
            </div>
            <div class="w-full h-full absolute transition-opacity duration-700 opacity-0 flex items-center justify-center text-white text-2xl">
                <img src="{{ asset('img/carrusel_3.png') }}" alt="carrusel.3">
            </div>
            <div class="w-full h-full absolute transition-opacity duration-700 opacity-0 flex items-center justify-center text-white text-2xl">
                <img src="{{ asset('img/carrusel_4.png') }}" alt="carrusel.4">
            </div>
        </div>
            <button id="prev" class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 md:w-11 md:h-11 rounded-full bg-white/90 border border-gray-200 flex items-center justify-center shadow-md hover:bg-white text-xl md:text-2xl">‹</button>
            <button id="next" class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 md:w-11 md:h-11 rounded-full bg-white/90 border border-gray-200 flex items-center justify-center shadow-md hover:bg-white text-xl md:text-2xl">›</button>
        </div>
    </main>


    <!-- Sección de Banners Promocionales -->
    <section class="max-w-[1200px] mx-auto px-3 py-6 md:py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Banner Envíos Gratis -->
            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <a href="#">
                    <div class="p-4">
                        <img src="{{ asset('img/banner_envios.jpg') }}" alt="banner.envios" class="w-full h-auto">
                    </div>
                </a>
            </div>

            <!-- Banner Rebajas -->
            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <a href="#">
                    <div class="p-4">
                    <img src="{{ asset('img/banner_rebajas.jpg') }}" alt="banner.rebajas" class="w-full h-auto">
                    </div>
                </a>
            </div>

            <!-- Banner SchoolShop -->
            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 sm:col-span-2 lg:col-span-1">
                <a href="{{ route('schoolshop') }}">
                    <div class="p-4">
                        <img src="{{ asset('img/banner_schoolshop.jpg') }}" alt="banner.sch" class="w-full h-auto">
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Sección de Novedades -->
    <section class="max-w-[1200px] mx-auto px-3 py-6 md:py-8">
        <!-- Título de la sección -->
        <div class="border-b-2 border-gray-200 mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 pb-2">NOVEDADES</h2>
        </div>

        <!-- Carrusel de Libros -->
        <div class="relative">
            <div id="books-carousel" class="flex overflow-x-auto gap-3 md:gap-4 scroll-smooth hide-scrollbar snap-x snap-mandatory pb-4">
                
                <!-- Libro 1 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-purple-100">
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/creepy.png') }}" alt="CREEPY" class="w-full h-auto">                          
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-800 truncate">CREEPY</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Alexander Rocks</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$299</p>
                        </div>
                    </a>
                </div>

                <!-- Libro 2 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-blue-100">
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/FNAF.jpeg') }}" alt="FIVE NIGHTS AT FREDDY'S" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-800 truncate">FIVE NIGHTS AT FREDDY'S</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Scott Cawthon</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$349</p>
                        </div>
                    </a>
                </div>

                <!-- Libro 3 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-pink-100">
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-10%</span>
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/MH.jpeg') }}" alt="MONSTER HIGH" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-800 truncate">MONSTER HIGH</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Lisi Harrison</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$270 <span class="text-xs line-through text-gray-400">$300</span></p>
                        </div>
                    </a>
                </div>

                <!-- Libro 4 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-yellow-100">
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-20%</span>
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/piense_hagase_rico.jpeg') }}" alt="PIENSE Y HÁGASE RICO" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-600 truncate">PIENSE Y HÁGASE RICO</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Napoleon Hill</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$240 <span class="text-xs line-through text-gray-400">$300</span></p>
                        </div>
                    </a>
                </div>

                <!-- Libro 5 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-red-100">
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-10%</span>
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/caperucita_roja.jpeg') }}" alt="CAPERUCITA ROJA" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-600 truncate">CAPERUCITA ROJA</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Charles Perrault</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$180 <span class="text-xs line-through text-gray-400">$200</span></p>
                        </div>
                    </a>
                </div>

                <!-- Libro 6 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-green-100">
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/historia_sexualidad.jpeg') }}" alt="Historia de la Sexualidad" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-600 truncate">Historia de la Sexualidad</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Michel Foucault</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$420</p>
                        </div>
                    </a>
                </div>

                <!-- Libro 7 -->
                <div class="min-w-[140px] w-[140px] sm:min-w-[180px] sm:w-[180px] md:min-w-[200px] md:w-[200px] flex-shrink-0 snap-start group">
                    <a href="#" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300 bg-indigo-100">
                            <div class="aspect-[3/4] flex items-center justify-center">
                                <img src="{{ asset('img/covers/Cien-Años-de-Soledad.jpg') }}" alt="Cien Años de Soledad" class="w-full h-auto">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs md:text-sm font-bold text-gray-600 truncate">Cien Años de Soledad</p>
                            <p class="text-xs md:text-sm mt-2 text-gray-800">Gabriel García Márquez</p>
                            <p class="text-sm md:text-base text-cyan-500 mt-2">$380</p>
                        </div>
                    </a>
                </div>

            </div>

            <!-- Botones de navegación - Solo desktop -->
            <button id="books-prev" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-11 h-11 rounded-full bg-white/90 border border-gray-300 items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-10">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="books-next" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-11 h-11 rounded-full bg-white/90 border border-gray-300 items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-10">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>

    <!-- Newsletter -->
    <div class="bg-[#ffa3c2] py-6 md:py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                <div class="flex items-center gap-4 text-white text-center md:text-left">
                    <i class="far fa-envelope text-4xl md:text-5xl"></i>
                    <div>
                        <h3 class="text-lg md:text-xl font-bold">Boletín de Novedades</h3>
                        <p class="text-xs md:text-sm">Suscríbete y estarás al tanto de nuestras novedades</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    <input type="email" placeholder="Email" 
                           class="px-4 py-2 w-full sm:w-60 md:w-80 rounded focus:outline-none text-sm md:text-base">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-950 whitespace-nowrap text-sm md:text-base">
                        Suscribir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Servicio al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-14 h-14 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-xl md:text-2xl"></i>
                    </div>
                    <h4 class="text-base md:text-lg font-bold mb-4">SERVICIO AL CLIENTE</h4>
                    <ul class="space-y-2 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-sky-400">Mis Pedidos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Favoritos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Direcciones</a></li>
                    </ul>
                </div>

                <!-- Políticas de la Tienda -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-14 h-14 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-xl md:text-2xl"></i>
                    </div>
                    <h4 class="text-base md:text-lg font-bold mb-4">POLÍTICAS DE LA TIENDA</h4>
                    <ul class="space-y-2 text-xs md:text-sm">
                        <li><a href="{{ route('sobre.nosotros') }}" class="hover:text-sky-400">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-sky-400">Aviso de privacidad</a></li>
                        <li><a href="#" class="hover:text-sky-400">Políticas de envío</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de venta en línea</a></li>
                    </ul>
                </div>

                <!-- Soporte al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-14 h-14 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-xl md:text-2xl"></i>
                    </div>
                    <h4 class="text-base md:text-lg font-bold mb-4">SOPORTE AL CLIENTE</h4>
                    <ul class="space-y-2 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-sky-400">Ayuda</a></li>
                        <li><a href="#" class="hover:text-sky-400">Contáctenos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Nuestras librerías</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de dinero electrónico</a></li>
                    </ul>
                </div>

                <!-- Facturación -->
                <div class="text-center sm:col-span-2 lg:col-span-1">
                    <div class="bg-sky-500 rounded-full w-14 h-14 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice text-xl md:text-2xl"></i>
                    </div>
                    <h4 class="text-base md:text-lg font-bold mb-4">FACTURACIÓN</h4>
                    <ul class="space-y-2 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-sky-400">Facturación electrónica</a></li>
                    </ul>
                </div>
            </div>

            <!-- Métodos de Pago -->
            <div class="border-t border-gray-700 pt-6 md:pt-8">
                <div class="flex justify-center items-center gap-4 md:gap-6 mb-6 overflow-x-auto pb-4">
                    
                    <div class="bg-gray-500 rounded px-3 py-2 h-8 md:h-10 flex items-center justify-center min-w-[60px]">
                        <img src="img/Paypal.png" alt="PayPal" class="h-auto">
                    </div>
                    <div class="bg-gray-500 rounded px-3 py-2 h-8 md:h-10 flex items-center justify-center min-w-[60px]">
                        <img src="img/MP.png" alt="Mercado Pago" class="h-auto">
                    </div>
                    <div class="bg-gray-500 rounded px-3 py-2 h-8 md:h-10 flex items-center justify-center min-w-[60px]">
                       <img src="img/VISA.png" alt="VISA" class="h-auto">
                    </div>
                    <div class="bg-gray-500 rounded px-3 py-2 h-8 md:h-10 flex items-center justify-center min-w-[60px]">
                        <img src="img/MC.png" alt="MASTERCARD" class="h-auto">
                    </div>
                    <div class="bg-gray-500 rounded px-3 py-2 h-8 md:h-10 flex items-center justify-center min-w-[60px]">
                        <img src="img/AE.png" alt="AMERICAN EXPRESS" class="h-auto">
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center text-xs md:text-sm text-gray-400 space-y-2">
                    <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                    <p class="hidden md:block">Los precios y la disponibilidad de los productos están sujetos a cambio sin previo aviso y solo se aplican para ventas en línea.</p>
                    <p>Los precios mostrados incluyen IVA.</p>
                </div>
            </div>
        </div>
    </footer>















<script>

// Menú móvil tipo dropdown
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const librosToggle = document.getElementById('libros-toggle');
const librosSubmenu = document.getElementById('libros-submenu');

mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// Submenú Libros
librosToggle.addEventListener('click', () => {
    librosSubmenu.classList.toggle('hidden');

    const icon = librosToggle.querySelector('i');
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
});


// Carrusel principal
const slides = document.querySelectorAll('#carousel .carousel-inner > div');
let current = 0;

const showSlide = index => {
    slides.forEach((slide, i) => {
        slide.style.opacity = (i === index) ? '1' : '0';
    });
}

document.getElementById('next').addEventListener('click', () => {
    current = (current + 1) % slides.length;
    showSlide(current);
});

document.getElementById('prev').addEventListener('click', () => {
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
});

setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
}, 5000);
</script>

<script>
// Script para el carrusel de libros
const booksCarousel = document.getElementById('books-carousel');
const booksPrev = document.getElementById('books-prev');
const booksNext = document.getElementById('books-next');

if (booksPrev && booksNext) {
    booksPrev.addEventListener('click', () => {
        booksCarousel.scrollBy({ left: -220, behavior: 'smooth' });
    });

    booksNext.addEventListener('click', () => {
        booksCarousel.scrollBy({ left: 220, behavior: 'smooth' });
    });
}
</script>

</body>
</html>