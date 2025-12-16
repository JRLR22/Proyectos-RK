<div class="bg-white shadow-md py-4 sticky top-10 z-[99]">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between gap-4">

            {{-- Logo --}}
            <div>
                <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
            </div>

            {{-- Buscador --}}
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

            {{-- Carrito --}}
            <div class="flex items-center gap-4">
                <a href="#" class="relative">
                    <i class="far fa-heart text-2xl text-gray-700"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        {{ Auth::check() ? Auth::user()->wishlist()->count() : 0 }}
                    </span>
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
