<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Gonvill')</title>
    {{-- el title funciona para poner el titulo de cada pagina --}}

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { position: relative; z-index: 0; }
        main .grid img { position: relative; z-index: 1 !important; }
        main .grid .absolute { z-index: 10 !important; }
        .sticky { z-index: 100 !important; }
    </style>
</head>

<body class="bg-gray-50">
    {{-- Nos ayudan a conectar el contenido de archivos de la carpeta partials: --}}
    {{-- HEADER SUPERIOR --}}
    @include('partials.header_top')

    {{-- HEADER PRINCIPAL --}}
    @include('partials.header_main')

    {{-- barra de navegación --}}
    @include('partials.navbar')

    {{-- CONTENIDO DE LA PÁGINA --}}
    <main>
        @yield('content')
    </main>

    {{-- NEWSLETTER --}}
    @include('partials.newsletter')

    {{-- FOOTER --}}
    @include('partials.footer')

</body>
</html>
