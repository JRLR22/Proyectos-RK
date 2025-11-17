<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Replica Librerías Gonvill</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-roboto text-gray-800 bg-white">


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
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-2xl text-gray-800"></i>
                        </div>
                        <span class="text-gray-700">Mi compra</span>
                    </a>
                </div>
            </div>
        </div>
    </div>


    </div>
  </header>
      <!--<a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a>-->
      <!-- Menú de Navegación -->
  <nav class="bg-white border-t border-b border-gray-200 sticky top-32 z-[98]">
      <div class="container mx-auto px-4">
          <ul class="flex gap-8 py-4 justify-center">
              <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Inicio</a></li>
              <li class="relative group">
                  <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a>
                  <!-- Megamenú -->
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
                                  <li><a href="#" class="text-gray-600 hover:text-sky-500">Libros para Todos</a></li>
                                  <li><a href="#" class="text-gray-600 hover:text-sky-500">Literatura</a></li>
                                  <li><a href="#" class="text-gray-600 hover:text-sky-500">Arte</a></li>
                                  <li><a href="#" class="text-gray-600 hover:text-sky-500">Autoayuda</a></li>
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
              <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Impresión bajo demanda</a></li>
              <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Sobre Nosotros</a></li>
              <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Nuestras librerías</a></li>
              <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Bolsa de trabajo</a></li>
              <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Ayuda</a></li>
              <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">SchoolShop</a></li>
          </ul>
      </div>
  </nav>

 <!-- HERO / BANNER con carrusel -->
<main class="max-w-[1200px] mx-auto px-3 py-4 relative">
  <div id="carousel" class="relative rounded overflow-hidden h-[420px]">
    
    <!-- Imágenes del carrusel -->
    <div class="carousel-inner absolute inset-0">
      <img src="{{ asset('img/carrusel_1.jpg') }}" class="w-full h-full object-cover absolute transition-opacity duration-700 opacity-100">
      <img src="{{ asset('img/carrusel_2.png') }}" class="w-full h-full object-cover absolute transition-opacity duration-700 opacity-0">
      <img src="{{ asset('img/carrusel_3.png') }}" class="w-full h-full object-cover absolute transition-opacity duration-700 opacity-0">
      <img src="{{ asset('img/carrusel_4.png') }}" class="w-full h-full object-cover absolute transition-opacity duration-700 opacity-0">
      <!-- agrega más fotos si quieres -->
    </div>

    <!-- flechas -->
    <button id="prev" class="absolute left-2 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 border border-gray-200 flex items-center justify-center shadow-md">‹</button>
    <button id="next" class="absolute right-2 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 border border-gray-200 flex items-center justify-center shadow-md">›</button>
  </div>
</main>

<!-- Sección de Banners Promocionales -->
<section class="max-w-[1200px] mx-auto px-3 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Banner Envíos Gratis -->
        <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <a href="#">
                <img src="{{ asset('img/banner_envios.jpg') }}" alt="Envíos Gratis en compras mayores a $399" class="w-full h-auto">
            </a>
        </div>

        <!-- Banner Rebajas -->
        <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <a href="#">
                <img src="{{ asset('img/banner_rebajas.jpg') }}" alt="Rebajas - Los libros a tu alcance" class="w-full h-auto">
            </a>
        </div>

        <!-- Banner SchoolShop -->
        <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <a href="{{ route('schoolshop') }}">
                <img src="{{ asset('img/banner_schoolshop.jpg') }}" alt="SchoolShop - Útiles escolares" class="w-full h-auto">
            </a>
        </div>
    </div>
</section>

<!-- Sección de Novedades -->
<section class="max-w-[1200px] mx-auto px-3 py-8">
    <!-- Título de la sección -->
    <div class="border-b-2 border-gray-200 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 pb-2">NOVEDADES</h2>
    </div>

    <!-- Carrusel de Libros -->
    <div class="relative">
        <div id="books-carousel" class="flex overflow-x-hidden gap-4 scroll-smooth">
            
            <!-- Libro 1 - CREEPY -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="https://www.gonvill.com.mx/libro/creepy-n-0129_43190494" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ asset('img/creepy.png') }}" alt="CREEPY" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">CREEPY</p>
                    </div>
                </a>
            </div>

            <!-- Libro 2 - FIVE NIGHTS AT FREDDY'S -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="#" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ asset('img/FNAF.jpeg') }}" alt="FIVE NIGHTS AT FREDDY'S" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">FIVE NIGHTS AT FREDDY'S</p>
                    </div>
                </a>
            </div>

            <!-- Libro 3 - MONSTER HIGH -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="#" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-10%</span>
                        <img src="{{ asset('img/MH.jpeg') }}" alt="MONSTER HIGH" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">MONSTER HIGH</p>
                    </div>
                </a>
            </div>

            <!-- Libro 4 - PIENSE Y HÁGASE RICO -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="#" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-20%</span>
                        <img src="{{ asset('img/piense_hagase_rico.jpeg') }}" alt="PIENSE Y HÁGASE RICO" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">PIENSE Y HÁGASE RICO</p>
                    </div>
                </a>
            </div>

            <!-- Libro 5 - CAPERUCITA ROJA -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="#" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">-10%</span>
                        <img src="{{ asset('img/caperucita_roja.jpeg') }}" alt="CAPERUCITA ROJA" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">CAPERUCITA ROJA</p>
                    </div>
                </a>
            </div>

            <!-- Libro 6 - Historia de la Sexualidad -->
              <div class="min-w-[200px] w-[200px] flex-shrink-0 group">
                <a href="#" class="block">
                    <div class="relative overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ asset('img/historia_sexualidad.jpeg') }}" alt="Historia de la Sexualidad" class="w-full h-auto">
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-600 truncate">Historia de la Sexualidad</p>
                    </div>
                </a>
            </div>

        </div>

        <!-- Botones de navegación del carrusel -->
        <button id="books-prev" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-11 h-11 rounded-full bg-white/90 border border-gray-300 flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-10">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="books-next" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-11 h-11 rounded-full bg-white/90 border border-gray-300 flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors z-10">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
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
<script>
// Script para el carrusel de libros
const booksCarousel = document.getElementById('books-carousel');
const booksPrev = document.getElementById('books-prev');
const booksNext = document.getElementById('books-next');

booksPrev.addEventListener('click', () => {
    booksCarousel.scrollBy({ left: -220, behavior: 'smooth' });
});

booksNext.addEventListener('click', () => {
    booksCarousel.scrollBy({ left: 220, behavior: 'smooth' });
});
</script>

<script>
  const slides = document.querySelectorAll('#carousel .carousel-inner img');
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

  // Cambio automático cada 5 segundos
  setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
  }, 5000);
</script>

</body>
</html>
