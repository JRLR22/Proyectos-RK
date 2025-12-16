<div class="border-t pt-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Reseñas de Clientes</h2>
        <p class="text-gray-600 mt-2">{{ $reviews->count() }} {{ $reviews->count() == 1 ? 'reseña' : 'reseñas' }}</p>
    </div>

    <!-- Estadísticas de Rating -->
    @if($reviews->count() > 0)
    <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Rating Promedio -->
            <div class="text-center">
                <div class="text-5xl font-bold text-gray-900 mb-2">
                    {{ number_format($reviews->avg('rating'), 1) }}
                </div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= round($reviews->avg('rating')) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                </div>
                <div class="text-gray-600">Basado en {{ $reviews->count() }} reseñas</div>
            </div>

            <!-- Distribución de Ratings -->
            <div class="space-y-2">
                @foreach([5, 4, 3, 2, 1] as $stars)
                @php
                    $count = $reviews->where('rating', $stars)->count();
                    $percentage = $reviews->count() > 0 ? ($count / $reviews->count()) * 100 : 0;
                @endphp
                <div class="flex items-center gap-2">
                    <span class="text-sm w-12">{{ $stars }} ⭐</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Formulario para Agregar Reseña -->
    @auth
        @if($canReview && !$hasReviewed)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">Escribe tu Reseña</h3>
                <form action="{{ route('reviews.store', $book->book_id) }}" method="POST">
                    @csrf
                    
                    <!-- Rating -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Calificación</label>
                        <div class="flex gap-2" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                    <svg class="w-10 h-10 text-gray-300 peer-checked:text-yellow-500 hover:text-yellow-400 transition fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Comentario -->
                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Tu Opinión</label>
                        <textarea 
                            name="comment" 
                            rows="4" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            placeholder="Comparte tu experiencia con este libro..."
                            required 
                            minlength="10" 
                            maxlength="1000"
                        ></textarea>
                        <p class="text-sm text-gray-500 mt-1">Mínimo 10 caracteres, máximo 1000</p>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Publicar Reseña
                    </button>
                </form>
            </div>
        @elseif($hasReviewed)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-800">Ya has dejado una reseña para este libro.</span>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-yellow-800">Solo puedes reseñar libros que hayas comprado.</span>
            </div>
        @endif
    @else
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p class="text-gray-600 mb-3">Inicia sesión para dejar una reseña</p>
            <a href="{{ route('mi.cuenta') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Iniciar Sesión
            </a>
        </div>
    @endauth

    <!-- Lista de Reseñas -->
    <div class="space-y-6">
        @forelse($reviews as $review)
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-bold text-lg">{{ $review->user->full_name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>
                            @if(Auth::check() && $review->user->canReviewBook($book->book_id))
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                    ✓ Compra Verificada
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm">{{ $review->created_at->format('d/m/Y') }}</p>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                
                @auth
                    @if($review->user_id === Auth::id())
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('reviews.edit', $review->review_id) }}" class="text-sm text-blue-600 hover:underline">
                                Editar
                            </a>
                            <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('¿Eliminar esta reseña?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                <p class="text-gray-500 text-lg">No hay reseñas aún</p>
                <p class="text-gray-400 mt-1">¡Sé el primero en compartir tu opinión!</p>
            </div>
        @endforelse
    </div>
</div>