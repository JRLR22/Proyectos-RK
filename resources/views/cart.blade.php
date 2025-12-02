<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito de Compras - Librerías Gonvill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            position: relative;
            z-index: 0;
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
                <a href="#" class="flex items-center gap-1 hover:underline">
                    <i class="far fa-user"></i> Mi cuenta
                </a>
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
                        <button class="bg-[#ffa3c2] hover:bg-[#DE5484] text-white px-6">
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
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-[#ffa3c2] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
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

    <!-- Contenido del Carrito -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Carrito de compras</h1>
        <div class="w-full h-[2px] bg-pink-400 mb-6"></div>

        <!-- Mensaje de alerta -->
        <div id="alert-message" class="hidden mb-4 p-4 rounded"></div>

        <!-- Carrito vacío -->
        <div id="empty-cart" class="hidden text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-600 mb-2">Tu carrito está vacío</h2>
            <p class="text-gray-500 mb-6">Agrega productos para comenzar tu compra</p>
            <a href="{{ route('inicio') }}" class="bg-[#ffa3c2] hover:bg-[#DE5484] text-white px-6 py-3 rounded inline-block">
                Continuar comprando
            </a>
        </div>

        <!-- Contenido del carrito -->
        <div id="cart-content" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tabla de productos -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Header de la tabla -->
                    <div class="bg-pink-400 text-white py-3 px-4">
                        <div class="grid grid-cols-12 gap-4 font-semibold">
                            <div class="col-span-5">ARTÍCULO</div>
                            <div class="col-span-2 text-center">CANTIDAD</div>
                            <div class="col-span-2 text-center">P. UNID.</div>
                            <div class="col-span-2 text-center">PRECIO</div>
                            <div class="col-span-1"></div>
                        </div>
                    </div>

                  <!-- Items del carrito -->
                    <div id="cart-items" class="divide-y divide-gray-200">
                        @forelse($cart as $item)
                        <div class="p-4">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-5 flex gap-4">
                                    <img src="{{ $item['cover_url'] ?? asset('img/no-image.png') }}" 
                                        alt="{{ $item['title'] }}" 
                                        class="w-20 h-28 object-cover rounded">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item['authors'] ?: 'Sin autor' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Stock: {{ $item['stock_available'] }}</p>
                                    </div>
                                </div>
                                <div class="col-span-2 flex items-center justify-center gap-2">
                                    <form action="{{ route('cart.update', $item['book_id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button type="submit" 
                                                class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                    </form>
                                    
                                    <input type="number" value="{{ $item['quantity'] }}" 
                                        class="w-16 text-center border border-gray-300 rounded py-1" readonly>
                                    
                                    <form action="{{ route('cart.update', $item['book_id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" 
                                                class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-span-2 text-center font-semibold text-gray-800">
                                    ${{ number_format($item['price'] ?? 0, 2) }}
                                </div>
                                <div class="col-span-2 text-center font-bold text-gray-900">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </div>
                                <div class="col-span-1 text-center">
                                    <form action="{{ route('cart.remove', $item['book_id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-gray-500">
                            Tu carrito está vacío
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Resumen y cupones -->
            <div class="lg:col-span-1">
                <!-- Cupón promocional -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código promocional</h3>
                    <p class="text-sm text-gray-600 mb-4">Si dispone de un código promocional introdúzcalo aquí</p>
                    <div class="flex gap-2">
                        <input type="text" id="coupon-input" placeholder="Código" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-sky-500">
                        <button onclick="applyCoupon()" class="bg-sky-400 hover:bg-sky-500 text-white px-4 py-2 rounded">
                            Aplicar
                        </button>
                    </div>
                    <div id="coupon-applied" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded">
                        <div class="flex justify-between items-center">
                            <span class="text-green-700 text-sm">Cupón aplicado</span>
                            <button onclick="removeCoupon()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resumen de compra -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Resumen de compra</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span>${{ number_format($summary['subtotal'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Descuento:</span>
                            <span>-${{ number_format($summary['discount'], 2) }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between text-xl font-bold text-gray-800">
                            <span>Total:</span>
                            <span>${{ number_format($summary['total'], 2) }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $summary['items_count'] }} producto{{ $summary['items_count'] != 1 ? 's' : '' }} • 
                            {{ $summary['total_weight_kg'] }} kg
                        </div>
                    </div>
                    
               
                    <a href="{{ route('inicio') }}" class="block text-center text-sky-500 hover:text-sky-600">
                        Continuar comprando
                    </a>

                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('¿Estás seguro de vaciar el carrito?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full mt-4 text-red-500 hover:text-red-700 text-sm">
                            Vaciar carrito
                        </button>
                    </form>
                </div>
                 <button onclick="" class="bg-sky-400 hover:bg-sky-500 text-white px-6 py-3 rounded mt-4 mx-auto block text-lg font-semibold">
                    Comprar
                </button>
            </div>
        </div>
    </main>

    <!-- Newsletter -->
    <div class="bg-[#ffa3c2] py-6 mt-12">
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
</body>
</html>