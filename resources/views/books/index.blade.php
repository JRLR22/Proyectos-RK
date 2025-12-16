@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Catálogo de Libros</h1>

    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Imagen del libro -->
                    <a href="{{ route('libros.show', $book->book_id) }}">
                        <img 
                            src="{{ $book->cover_url }}" 
                            alt="{{ $book->title }}"
                            class="w-full h-64 object-cover"
                        >
                    </a>

                    <!-- Información del libro -->
                    <div class="p-4">
                        <!-- Título -->
                        <a href="{{ route('libros.show', $book->book_id) }}" class="block">
                            <h3 class="font-bold text-lg mb-2 hover:text-blue-600 line-clamp-2">
                                {{ $book->title }}
                            </h3>
                        </a>

                        <!-- Autor -->
                        <p class="text-gray-600 text-sm mb-3 line-clamp-1">
                            {{ $book->authors_list }}
                        </p>

                        <!-- Categoría -->
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mb-3">
                            {{ $book->category->name ?? 'Sin categoría' }}
                        </span>

                        <!-- Precio -->
                        <div class="mb-3">
                            @if($book->discount_percentage > 0)
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 line-through text-sm">
                                        ${{ number_format($book->price, 2) }}
                                    </span>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                        -{{ $book->discount_percentage }}%
                                    </span>
                                </div>
                                <div class="text-green-600 font-bold text-xl">
                                    ${{ number_format($book->discounted_price, 2) }}
                                </div>
                            @else
                                <div class="text-gray-900 font-bold text-xl">
                                    ${{ number_format($book->price, 2) }}
                                </div>
                            @endif
                        </div>

                        <!-- Estado -->
                        @if($book->status === 'En stock')
                            <span class="text-green-600 text-sm font-semibold">✓ Disponible</span>
                        @else
                            <span class="text-red-600 text-sm font-semibold">✗ Agotado</span>
                        @endif

                        <!-- Botón -->
                        @if($book->status === 'En stock')
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button 
                                    type="submit" 
                                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
                                >
                                    🛒 Agregar al carrito
                                </button>
                            </form>
                        @else
                            <button 
                                disabled 
                                class="w-full bg-gray-400 text-white py-2 rounded cursor-not-allowed mt-3"
                            >
                                Agotado
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-xl">No hay libros disponibles</p>
        </div>
    @endif
</div>
@endsection