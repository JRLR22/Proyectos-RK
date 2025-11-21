<nav class="bg-white border-t border-b border-gray-200 sticky top-32 z-[98]">
    <div class="container mx-auto px-4">
        <ul class="flex gap-8 py-4 justify-center">
            <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Inicio</a></li>

            <li class="relative group">
                <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Libros ▾</a>

                {{-- Megamenú --}}
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

            <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Impresión bajo demanda</a></li>
            <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Sobre Nosotros</a></li>
            <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Nuestras librerías</a></li>
            <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Bolsa de trabajo</a></li>
            <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">Ayuda</a></li>
            <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1">SchoolShop</a></li>
        </ul>
    </div>
</nav>
