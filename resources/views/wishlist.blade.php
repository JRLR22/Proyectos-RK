@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Lista de Deseos</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($wishlist as $item)
            <div class="border rounded-lg p-4 shadow">
                <img src="{{ $item['book']['cover_url'] }}" alt="{{ $item['book']['title'] }}" class="w-full h-48 object-cover mb-4 rounded">
                
                <h3 class="font-bold text-lg mb-2">{{ $item['book']['title'] }}</h3>
                <p class="text-gray-600 mb-2">{{ $item['book']['authors'] }}</p>
                <p class="text-xl font-bold text-green-600 mb-4">${{ number_format($item['book']['discounted_price'] ?? $item['book']['price'], 2) }}</p>
                
                <div class="flex gap-2">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $item['book']['book_id'] }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Agregar al Carrito
                        </button>
                    </form>
                    
                    <form action="{{ route('wishlist.remove', $item['wishlist_id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            ✕
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500 text-xl mb-4">Tu lista de deseos está vacía</p>
                <a href="{{ route('inicio') }}" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                    Explorar Libros
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection