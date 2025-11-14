<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión Bajo Demanda - Librerías Gonvill</title>
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
                    <a href="{{ route('profile') }}" class="flex items-center gap-1 hover:underline">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->first_name }}
                    </a>
                @else
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
                <div>
                    <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
                </div>
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
                <li class="relative group">
                    <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a>
                    <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-2xl border border-gray-200 w-[800px] p-6 z-[150]">
                        <div class="grid grid-cols-3 gap-6">
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
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Sociales</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Impresión bajo demanda</a></li>
                <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Sobre Nosotros</a></li>
                <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Nuestras librerías</a></li>
                <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Bolsa de trabajo</a></li>
                <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Ayuda</a></li>
                <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">SchoolShop</a></li>
            </ul>
        </div>
    </nav>
   
<div class="container mx-auto px-4 py-10">

    <h2 class="text-2xl font-bold text-gray-800 mb-2">Novedades</h2>
    <div class="w-full h-[6px] bg-gray-500 mb-8"></div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">

        @foreach ($books as $book)
            <div class="bg-white shadow-md hover:shadow-lg transition rounded-lg p-3 text-center">

                {{-- Portada --}}
                <a href="#">
                    <img src="{{ $book->cover_url }}"
                         alt="{{ $book->title }}"
                         class="w-full h-48 object-contain mx-auto">
                </a>

                {{-- Título --}}
                <h3 class="mt-3 text-sm font-bold text-gray-800 leading-tight">
                    {{ strtoupper($book->title) }}
                </h3>

                {{-- Autor --}}
                @if($book->authors_list)
                    <p class="text-xs text-gray-500 mt-1">
                        {{ strtoupper($book->authors_list) }}
                    </p>
                @endif

                {{-- Stock --}}
                <p class="text-xs mt-1 {{ $book->status == 'En stock' ? 'text-green-600' : 'text-red-500' }}">
                    {{ $book->status == 'En stock' ? 'EN STOCK' : 'AGOTADO' }}
                </p>

                {{-- Precio --}}
                <p class="text-lg font-bold text-sky-600 mt-2">
                    ${{ number_format($book->price, 2) }}
                </p>

                {{-- Botón --}}
                <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                    Añadir <i class="fas fa-shopping-cart"></i>
                </button>

            </div>
        @endforeach

    </div>

                <!-- Paginación -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    {{-- Botón Primera página --}}
                    @if ($books->onFirstPage())
                        <span class="px-4 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">Primera</span>
                    @else
                        <a href="{{ $books->url(1) }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Primera</a>
                    @endif

                    {{-- Números de página --}}
                    @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if ($page == $books->currentPage())
                            <span class="px-4 py-2 bg-sky-500 text-white rounded">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                        @endif
                    @endforeach

                    {{-- Botón Última página --}}
                    @if ($books->hasMorePages())
                        <a href="{{ $books->url($books->lastPage()) }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Última</a>
                    @else
                        <span class="px-4 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">Última</span>
                    @endif
                </div>
            </main>
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
                <div class="flex justify-center items-center gap-6 mb-6">
                    <img src="{{ asset('img/Paypal.png') }}" alt="PayPal" class="h-10">
                    <img src="{{ asset('img/MP.png') }}" alt="Mercado Pago" class="h-10">
                    <img src="{{ asset('img/AE.png') }}" alt="American Express" class="h-10">
                    <img src="{{ asset('img/VISA.png') }}" alt="Visa" class="h-10">
                    <img src="{{ asset('img/MC.png') }}" alt="Mastercard" class="h-10">
                </div>
                <div class="text-center text-sm text-gray-400 space-y-2">
                    <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                    <p>Los precios y la disponibilidad de los productos están sujetos a cambio sin previo aviso.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>