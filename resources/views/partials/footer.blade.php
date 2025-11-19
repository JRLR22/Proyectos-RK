<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">

        <div class="grid grid-cols-4 gap-8 mb-8">

            {{-- Servicio --}}
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

            {{-- Políticas --}}
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

            {{-- Soporte --}}
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

            {{-- Facturación --}}
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
                <img src="{{ asset('img/Paypal.png') }}" class="h-10">
                <img src="{{ asset('img/MP.png') }}" class="h-10">
                <img src="{{ asset('img/AE.png') }}" class="h-10">
                <img src="{{ asset('img/VISA.png') }}" class="h-10">
                <img src="{{ asset('img/MC.png') }}" class="h-10">
            </div>

            <div class="text-center text-sm text-gray-400 space-y-2">
                <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                <p>Los precios y la disponibilidad se aplican solo a ventas en línea.</p>
                <p>Los precios incluyen IVA.</p>
            </div>
        </div>

    </div>
</footer>
