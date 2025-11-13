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
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Novedades</a></li>
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
   
    <!-- Contenido Principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-6">
            <!-- Sidebar con filtros dinámicos -->
            <aside class="w-64 flex-shrink-0">
                <form method="GET" action="{{ route('impresion.demanda') }}" id="filterForm">
                    <!-- Novedades -->
                    <div class="mb-6">
                        <h3 class="text-[#AD1850] font-bold text-lg mb-3">NOVEDADES</h3>
                        <div class="space-y-2">
                            <label class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="dias" value="30" 
                                           {{ request('dias') == '30' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>Últimos 30 días</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['ultimos_30_dias'] }})</span>
                            </label>
                            <label class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="dias" value="60"
                                           {{ request('dias') == '60' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>Últimos 60 días</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['ultimos_60_dias'] }})</span>
                            </label>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="mb-6">
                        <h3 class="text-[#AD1850] font-bold text-lg mb-3">PRECIOS</h3>
                        <div class="space-y-2 text-sm">
                            <label class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="precio" value="menos_100"
                                           {{ request('precio') == 'menos_100' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>Menos de 100 pesos</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['menos_100'] }})</span>
                            </label>
                            <label class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="precio" value="100_200"
                                           {{ request('precio') == '100_200' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>De 100 a 200 pesos</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['entre_100_200'] }})</span>
                            </label>
                            <label class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="precio" value="200_300"
                                           {{ request('precio') == '200_300' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>De 200 a 300 pesos</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['entre_200_300'] }})</span>
                            </label>
                            <label class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="precio" value="300_800"
                                           {{ request('precio') == '300_800' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>De 300 a 800 pesos</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['entre_300_800'] }})</span>
                            </label>
                            <label class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1 cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="precio" value="mas_800"
                                           {{ request('precio') == 'mas_800' ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>Más de 800 pesos</span>
                                </div>
                                <span class="text-gray-400">({{ $stats['mas_800'] }})</span>
                            </label>
                        </div>
                    </div>

                    <!-- Disponibilidad -->
                    <div>
                        <h3 class="text-[#AD1850] font-bold text-lg mb-3">DISPONIBILIDAD</h3>
                        <label class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1 cursor-pointer">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="disponibilidad" value="si"
                                       {{ request('disponibilidad') == 'si' ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span>Disponibilidad</span>
                            </div>
                            <span class="text-gray-400">({{ $stats['disponibles'] }})</span>
                        </label>
                    </div>

                    <!-- Botón limpiar filtros -->
                    @if(request()->hasAny(['dias', 'precio', 'disponibilidad']))
                    <div class="mt-6">
                        <a href="{{ route('impresion.demanda') }}" 
                           class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                            Limpiar filtros
                        </a>
                    </div>
                    @endif
                </form>
            </aside>

            <!-- Área de Productos -->
            <main class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $books->total() }} resultados</h2>
                    <div class="flex items-center gap-4">
                        <form method="GET" action="{{ route('impresion.demanda') }}" class="flex items-center gap-4">
                            <!-- Mantener filtros actuales -->
                            @foreach(request()->except(['orden', 'per_page', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <select name="orden" onchange="this.form.submit()" class="border border-gray-300 px-3 py-2 rounded">
                                <option value="">Ordenar por</option>
                                <option value="disponibilidad" {{ request('orden') == 'disponibilidad' ? 'selected' : '' }}>Disponibilidad</option>
                                <option value="titulo" {{ request('orden') == 'titulo' ? 'selected' : '' }}>Título</option>
                                <option value="autor" {{ request('orden') == 'autor' ? 'selected' : '' }}>Autor</option>
                                <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                <option value="fecha_edicion" {{ request('orden') == 'fecha_edicion' ? 'selected' : '' }}>Fecha edición</option>
                                <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            </select>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600">Ver</span>
                                <select name="per_page" onchange="this.form.submit()" class="border border-gray-300 px-2 py-1 rounded">
                                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                    <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40</option>
                                    <option value="60" {{ request('per_page') == 60 ? 'selected' : '' }}>60</option>
                                </select>
                                <span class="text-gray-600">Por página</span>
                            </div>
                        </form>
                        <div class="flex gap-2">
                            <button class="p-2 bg-sky-500 text-white rounded"><i class="fas fa-th"></i></button>
                            <button class="p-2 border border-gray-300 rounded"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Grid de Productos DINÁMICO -->
                @if($books->count() > 0)
                <div class="grid grid-cols-5 gap-4">
                    @foreach($books as $book)
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            @if($book->type == 'Impresión bajo demanda')
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded z-10">
                                Impresión bajo demanda
                            </span>
                            @endif
                            
                            <img src="{{ $book->cover_url }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-64 object-cover"
                                 onerror="this.src='https://via.placeholder.com/200x280/6366f1/ffffff?text=Sin+Imagen'">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center line-clamp-2 h-10" title="{{ $book->title }}">
                                {{ Str::upper($book->title) }}
                            </h3>
                            <p class="text-xs text-gray-600 text-center mb-2">
                                {{ $book->authors_list ?: ($book->publisher ?: 'AAVV') }}
                            </p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">
                                ${{ number_format($book->price, 2) }}
                            </p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
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
                @else
                <div class="text-center py-16">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No se encontraron libros</h3>
                    <p class="text-gray-500">Intenta ajustar los filtros o limpiar la búsqueda</p>
                </div>
                @endif
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