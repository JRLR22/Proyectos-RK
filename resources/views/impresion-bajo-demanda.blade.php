<!DOCTYPE html>
<html lang="es">
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
                <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
            </div>
                <!-- Buscador -->
                <div class="flex-1 max-w-2xl">
                    <div class="flex">
                        <input type="text" placeholder="Título, Autor, ISBN, Código Gonvill" 
                               class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-sky-500">
                        <button class="bg-[#ffa3c2] hover:bg-[#DE5484] text-white px-6 hover:bg-sky-600">
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
   
    <!-- Contenido Principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-6">
            <!-- Sidebar -->
            <aside class="w-64 flex-shrink-0">
                <!-- Novedades -->
                <div class="mb-6">
                    <h3 class="text-[#AD1850] font-bold text-lg mb-3">NOVEDADES</h3>
                    <div class="space-y-2">
                        <a href="#" class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1">
                            <span>Últimos 30 días</span>
                            <span class="text-gray-400">(173)</span>
                        </a>
                        <a href="#" class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1">
                            <span>Últimos 60 días</span>
                            <span class="text-gray-400">(358)</span>
                        </a>
                    </div>
                </div>

                <!-- Precios -->
                <div class="mb-6">
                    <h3 class="text-[#AD1850] font-bold text-lg mb-3">PRECIOS</h3>
                    <div class="space-y-2 text-sm">
                        <a href="#" class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1">
                            <span>Menos de 100 pesos</span>
                            <span class="text-gray-400">(3)</span>
                        </a>
                        <a href="#" class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1">
                            <span>De 100 a 200 pesos</span>
                            <span class="text-gray-400">(5,662)</span>
                        </a>
                        <a href="#" class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1">
                            <span>De 200 a 300 pesos</span>
                            <span class="text-gray-400">(16,225)</span>
                        </a>
                        <a href="#" class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1">
                            <span>De 300 a 800 pesos</span>
                            <span class="text-gray-400">(42,578)</span>
                        </a>
                        <a href="#" class="flex justify-between text-[#2E2A2A] font-medium px-2 py-1">
                            <span>Más de 800 pesos</span>
                            <span class="text-gray-400">(3,645)</span>
                        </a>
                    </div>
                </div>

                <!-- Disponibilidad -->
                <div>
                    <h3 class="text-[#AD1850] font-bold text-lg mb-3">DISPONIBILIDAD</h3>
                    <a href="#" class="flex justify-between text-[#2E2A2A] hover:text-[#5C5454] font-medium px-2 py-1">
                        <span>Disponibilidad</span>
                        <span class="text-gray-400">(68,309)</span>
                    </a>
                </div>
            </aside>

            <!-- Área de Productos -->
            <main class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">68313 resultados</h2>
                    <div class="flex items-center gap-4">
                        <select class="border border-gray-300 px-3 py-2 rounded">
                            <option>Ordenar por</option>
                            <option>Disponibilidad</option>
                            <option>Título</option>
                            <option>Autor</option>
                            <option>Precio</option>
                            <option>Fecha edición</option>
                        </select>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600">Ver</span>
                            <select class="border border-gray-300 px-2 py-1 rounded">
                                <option>20</option>
                                <option>40</option>
                                <option>60</option>
                            </select>
                            <span class="text-gray-600">Por página</span>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2 bg-sky-500 text-white rounded"><i class="fas fa-th"></i></button>
                            <button class="p-2 border border-gray-300 rounded"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Grid de Productos -->
                <div class="grid grid-cols-5 gap-4">
                    <!-- Producto 1 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded">Impresión bajo demanda</span>
                            <img src="img\Impresion-bd1.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">DIAMANTES GEMELOS</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$310.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Producto 2 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded">Impresión bajo demanda</span>
                            <img src="img\Impresion-bd2.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>

                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">REVISTAS DIÁSPORA(S)</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$1,139.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Producto 3 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded">Impresión bajo demanda</span>
                            <img src="img\Impresion-bd3.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>

                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TRAS LAS HUELLAS DEL CHAMÁN INKA</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$315.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Producto 4 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded">Impresión bajo demanda</span>
                            <img src="img/Impresion-bd4.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>

                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">HISTORIA GENERAL DE LAS INDIAS</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$857.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Producto 5 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded">Impresión bajo demanda</span>
                            <img src="img\Impresion-bd5.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>

                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">FAMILIA Y CRIANZA EN LA DIVERSIDAD</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$305.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Producto 6 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="img\Impresion-bd6.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">LA TEORÍA DEL APEGO EN LA FAMILIA</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AAVV</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$430.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Producto 7 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="img\Impresion-bd7.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">APEGO Y MOTIVACIÓN</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">MARRONE, MARIO</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$420.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Producto 8 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="img\Impresion-bd8.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">EVALUACIÓN DE LOS TRASTORNOS</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">SILIN, PEDRO</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$400.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Producto 9 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="img\Impresion-bd9.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">SED DE PIEL</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">LUCAS MATHEU, MANUEL</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$515.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Producto 10 -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="img\Impresion-bd10.jpeg" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">INVESTIGACIÓN CUALITATIVA</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">ZHIZHKO, ELENA ANATOLIEVNA</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$245.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Productos 11-20 (similares) -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/2e8b57/ffffff?text=Libro+11" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$320.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/8b4513/ffffff?text=Libro+12" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$380.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/daa520/ffffff?text=Libro+13" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$290.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/bc8f8f/ffffff?text=Libro+14" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$450.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/483d8b/ffffff?text=Libro+15" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$340.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/20b2aa/ffffff?text=Libro+16" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$410.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/ff8c00/ffffff?text=Libro+17" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$365.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/9370db/ffffff?text=Libro+18" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$275.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/cd5c5c/ffffff?text=Libro+19" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$530.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/200x280/228b22/ffffff?text=Libro+20" alt="Libro" class="w-full h-64 object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 text-center">TÍTULO DEL LIBRO</h3>
                            <p class="text-xs text-gray-600 text-center mb-2">AUTOR</p>
                            <p class="text-sky-600 font-bold text-center text-lg mb-2">$395.00</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                                Añadir <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button class="px-4 py-2 bg-sky-500 text-white rounded">01</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">02</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">03</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">04</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">05</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">06</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">07</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">08</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">09</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">10</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">»</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Última</button>
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