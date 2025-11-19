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

<!--AQUI PONER CODIGO-->
<!--PESTAÑA SOBRE NOSOTROS-->

<section class="bg-[#fef0f5] py-12">
    <div class="container mx-auto px-16 md:px-20 lg:px-24">
        <!-- Título -->
        <h1 class="text-center text-4xl font-bold text-[#ff6392] mb-8 uppercase tracking-wide">Sobre Nosotros</h1>

        <!-- Introducción
        <p class="text-center text-gray-700 max-w-3xl mx-auto mb-12 leading-relaxed">
            En <strong class="text-[#ff6392]">Librerías Gonvill</strong> fomentamos la lectura, la educación y el amor por los libros desde hace más de medio siglo,
            ofreciendo siempre un espacio de conocimiento y cultura para todos.
        </p>-->

        <!-- Imagen -->
        <div class="relative mb-16">
            <img src="{{ asset('img/010-es-banner-nosotros2.jpg') }}" alt="Librería Gonvill Sobre Nosotros" 
                 class="w-full rounded-2xl shadow-lg border border-[#ffd7e3]">
            <div class="absolute inset-0 bg-[#ffa3c2]/20 rounded-2xl"></div>
        </div> 

        <!-- Contenido principal TODO -->
        <div> <!--Ponerlo en modo de columna:  class="grid md:grid-cols-2 gap-10 text-gray-700 leading-relaxed"-->
            <div class="space-y-6">
                <!-- AQUÍ INICIA "NUESTRA HISTORIA" -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Nuestra Historia</h2>
                    <p class="text-justify"> LIBRERIAS GONVILL abre su primera librería en Guadalajara, Jal. en 1967.
                    </p>
                    <p class="text-justify">
                        A través de un desarrollo y evolución constante, 
                        en la actualidad somos una cadena de 31 librerías en las ciudades de Guadalajara, 
                        Puerto Vallarta, León, Aguascalientes, San Luis Potosí, Querétaro, Torreón, Monterrey, 
                        Chihuahua, Culiacán y Mazatlán.
                    </p>
                    <p class="text-justify">
                        La librería virtual <a href="http://www.gonvill.com.mx/">www.gonvill.com.mx</a> 
                        brinda servicio nacional e internacionalmente las 24 hrs. de los 7 días de la semana.
                    </p>
                    <p class="text-justify">
                        Adicionalmente, el Centro de Distribución Nacional de LIBRERIAS GONVILL en Guadalajara 
                        atiende pedidos de mayoreo de escuelas, universidades, empresas, entidades 
                        gubernamentales, etc. y  participa en licitaciones en todo México. 
                    </p>
                    <p class="text-justify">
                        Nuestras librerías ofrecen un servicio rápido y eficiente, y tienen el más amplio 
                        surtido en libros de interés general, profesionales, académicos y de texto para todos 
                        los niveles educativos en español, inglés y otros idiomas, así como libros de lectura 
                        para niños, jóvenes y adultos en inglés y en español.
                    </p>
                </div>
                <!-- AQUÍ INICIA "MISIÓN" -->
                <div>
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Misión</h2>
                    <p class="text-justify">
                        Apoyar el fomento a la lectura en nuestro país, brindando espacios dignos 
                        y agradables al libro para su adecuada exhibición y comercialización, y 
                        hacerlo llegar a todo el territorio nacional en tiempos razonables y a precios 
                        justos.
                    </p>
                </div>
            </div>
                <!-- AQUÍ INICIA "VISIÓN" -->
            <div class="space-y-6">
                <div>
                    <br><h2 class="text-2xl font-semibold text-[#ff6392] mb-2">Visión</h2>
                    <p class="text-justify">
                        Participar activamente en el desarrollo cultural y educativo de México, 
                        como una empresa líder y confiable para los distintos segmentos de mercado a los que llegamos. 
                        <br>
                        Apoyar y participar en la evolución de la industria editorial hacia 
                        nuevas formas de distribución y comercialización de contenidos en 
                        formato electrónico e impreso.
                    </p>
                </div>

            <!-- VALORES-->
                <div>
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Valores</h2>
                    <ul class="list-disc pl-6">
                        <li>Honestidad, ética y compromiso con nuestra actividad.</li>
                        <li>Respeto a nuestros clientes, proveedores, colaboradores y empleados, instituciones públicas, y en general a la comunidad a la que servimos.</li>
                        <li>EL LIBRO en sus diversos formatos ocupa un lugar especial en el desarrollo de la humanidad, por ser transmisor de la historia y de la cultura de generación en generación. </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- AQUÍ INICIA "FRASE" -->
        <div class="mt-16 text-center bg-[#ffd7e3] py-10 px-6 rounded-2xl shadow-inner">
            <p class="text-xl italic text-gray-700 max-w-2xl mx-auto">
                “HEMOS RECORRIDO UN LARGO CAMINO. . . MUCHO MÁS NOS QUEDA POR ALCANZAR”
            </p>
        </div>
    </div>
</section>

<!--FIN PESTAÑA SOBRE NOSOTROS-->

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