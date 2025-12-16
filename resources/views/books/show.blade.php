@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('inicio') }}" class="text-blue-600 hover:underline">Inicio</a>
        <span class="mx-2">/</span>
        <a href="#" class="text-blue-600 hover:underline">{{ $book->category->name ?? 'Libros' }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $book->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Imagen del libro -->
        <div class="lg:col-span-1">
            <div class="sticky top-4">
                <img 
                    src="{{ $book->cover_url }}" 
                    alt="{{ $book->title }}"
                    class="w-full rounded-lg shadow-lg mb-4"
                >
                
                <!-- Botón de Wishlist -->
                @auth
                    <form action="{{ route('wishlist.add', $book->book_id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="w-full bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300 flex items-center justify-center gap-2">
                            ❤️ Agregar a favoritos
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Información del libro -->
        <div class="lg:col-span-2">
            <!-- Título y autor -->
            <h1 class="text-4xl font-bold mb-2">{{ $book->title }}</h1>
            @if($book->subtitle)
                <h2 class="text-xl text-gray-600 mb-3">{{ $book->subtitle }}</h2>
            @endif
            
            <p class="text-lg text-gray-700 mb-4">
                Por: <span class="font-semibold">{{ $book->authors_list }}</span>
            </p>

            <!-- Rating y reseñas -->
            <div class="flex items-center gap-3 mb-6">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($book->average_rating) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-gray-600">
                    {{ number_format($book->average_rating, 1) }} 
                    ({{ $book->reviews_count }} {{ $book->reviews_count == 1 ? 'reseña' : 'reseñas' }})
                </span>
            </div>

            <!-- Precio -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                @if($book->discount_percentage > 0)
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-2xl text-gray-500 line-through">${{ number_format($book->price, 2) }}</span>
                        <span class="bg-red-500 text-white px-3 py-1 rounded font-bold">
                            -{{ $book->discount_percentage }}%
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-green-600 mb-2">
                        ${{ number_format($book->discounted_price, 2) }}
                    </div>
                    <p class="text-sm text-gray-600">
                        Ahorras: ${{ number_format($book->price - $book->discounted_price, 2) }}
                    </p>
                @else
                    <div class="text-4xl font-bold text-gray-900">
                        ${{ number_format($book->price, 2) }}
                    </div>
                @endif

                <!-- Stock -->
                <div class="mt-4">
                    @if($book->status === 'En stock')
                        <span class="text-green-600 font-semibold">✓ Disponible</span>
                        @if($book->stock_quantity < 10)
                            <span class="text-orange-600 text-sm ml-2">
                                (Solo quedan {{ $book->stock_quantity }} unidades)
                            </span>
                        @endif
                    @else
                        <span class="text-red-600 font-semibold">✗ Agotado</span>
                    @endif
                </div>
            </div>

            <!-- Botón de agregar al carrito -->
            @if($book->status === 'En stock')
                <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                    
                    <div class="flex gap-4 items-center mb-4">
                        <label class="font-semibold">Cantidad:</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            value="1" 
                            min="1" 
                            max="{{ $book->stock_quantity }}"
                            class="w-20 border border-gray-300 rounded px-3 py-2 text-center"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition"
                    >
                        🛒 Agregar al carrito
                    </button>
                </form>
            @else
                <button 
                    disabled 
                    class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold text-lg cursor-not-allowed"
                >
                    Producto agotado
                </button>
            @endif

            <!-- Detalles del libro -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-2xl font-bold mb-4">Detalles del Libro</h3>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if($book->isbn)
                        <div>
                            <span class="font-semibold">ISBN:</span>
                            <span class="text-gray-700">{{ $book->isbn }}</span>
                        </div>
                    @endif

                    @if($book->publisher)
                        <div>
                            <span class="font-semibold">Editorial:</span>
                            <span class="text-gray-700">{{ $book->publisher }}</span>
                        </div>
                    @endif

                    @if($book->publication_year)
                        <div>
                            <span class="font-semibold">Año:</span>
                            <span class="text-gray-700">{{ $book->publication_year }}</span>
                        </div>
                    @endif

                    @if($book->pages)
                        <div>
                            <span class="font-semibold">Páginas:</span>
                            <span class="text-gray-700">{{ $book->pages }}</span>
                        </div>
                    @endif

                    @if($book->language)
                        <div>
                            <span class="font-semibold">Idioma:</span>
                            <span class="text-gray-700">{{ $book->language }}</span>
                        </div>
                    @endif

                    @if($book->edition)
                        <div>
                            <span class="font-semibold">Edición:</span>
                            <span class="text-gray-700">{{ $book->edition }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descripción -->
            @if($book->description)
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-2xl font-bold mb-4">Descripción</h3>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $book->description }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Reseñas -->
    @include('partials.reviews', [
        'reviews' => $book->reviews,
        'book' => $book,
        'canReview' => Auth::check() ? Auth::user()->canReviewBook($book->book_id) : false,
        'hasReviewed' => Auth::check() ? Auth::user()->hasReviewedBook($book->book_id) : false
    ])
</div>
@endsection