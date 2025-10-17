<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Replica Gonvill - Demo</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-roboto text-gray-800 bg-white">

  <!-- BARRA SUPERIOR -->
  <div class="bg-[#19a4d6] text-white text-sm">
    <div class="max-w-[1200px] mx-auto flex flex-wrap justify-between items-center gap-2 px-3 py-2">
      <div class="flex gap-2 items-center">
        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center" title="Facebook">
          <img src="{{ asset('img/facebook.png') }}" alt="facebook" class="w-auto h-auto">
        </div>
        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center" title="Twitter">
          <img src="{{ asset('img/twitter.png') }}" alt="twitter" class="w-auto h-auto">
        </div>
        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center" title="Instagram">
          <img src="{{ asset('img/instagram.png') }}" alt="instagram" class="w-auto h-auto">
        </div>
        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center" title="YouTube">
          <img src="{{ asset('img/youtube.png') }}" alt="youtube" class="w-auto h-auto">
        </div>
      </div>

      <div class="flex gap-4 items-center text-lg">
        <div>Contacto</div>
        <div>|</div>
        <div>Mi cuenta</div>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="sticky top-0 z-50 border-b border-gray-200 bg-white">
    <div class="max-w-[1200px] mx-auto flex flex-wrap items-center gap-4 px-3 py-4">
      
      <!-- logo -->
      <div class="w-[230px] flex items-center gap-2">
        <img src="{{ asset('img/logoGonvill.png') }}" alt="Logo" class="w-full h-auto block">
      </div>

      <!-- Barra de busqueda -->
      <div class="flex-1 flex items-center gap-2">
        <div class="flex-1 flex items-center border border-[#d7e6ee] rounded overflow-hidden bg-white">
          <input type="search" placeholder="Título, Autor, ISBN, Código Gonvill"
                 class="flex-1 px-4 py-3 text-base outline-none" aria-label="buscar">
          <button class="bg-[#1aa6d9] hover:bg-[#178bb3] text-white px-4 py-2 border-l border-gray-100 transition-colors duration-200" aria-label="buscar">
              <img src="{{ asset('img/lupa.png') }}" alt="Lupa" class="w-9 h-9">
          </button>

        </div>
      </div>

      <!-- actions -->
      <div class="flex items-center gap-3">
        <div class="flex flex-col items-center text-[12px] text-gray-500">
          <img src="{{ asset('img/favorito.png') }}" alt="favoritos" class="w-9 h-9">
          Lista
        </div>

        <div class="flex flex-col items-center text-[12px] text-gray-500">
          <img src="{{ asset('img/carritodecompras.png') }}" alt="Carrito" class="w-9 h-9">
          Mi compra
        </div>
      </div>

    </div>
  </header>

  <!-- NAV -->
  <nav class="border-t border-b border-gray-200 bg-white">
    <div class="max-w-[1200px] mx-auto flex flex-wrap items-center gap-4 px-3 py-3 overflow-x-auto">
      <a href="" class="text-[#17678a] font-medium px-2 py-1">Inicio</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Libros ▾</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Impresión bajo demanda</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Sobre Nosotros</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Nuestras librerías</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Bolsa de trabajo</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">Ayuda</a>
      <a href="#" class="text-[#17678a] font-medium px-2 py-1">SchoolShop</a>
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


  <!-- Pie -->
  <footer class="max-w-[1200px] mx-auto px-3 py-6 text-gray-500 border-t border-gray-200">
  </footer>

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
