<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Mostrar todos los libros
     */
    public function index()
    {
        $books = Book::with(['authors', 'category'])
            ->where('status', 'En stock')
            ->paginate(20);

        return view('books.index', compact('books'));
    }

    /**
     * Mostrar un libro individual con sus reseñas
     */
    public function show($id)
    {
        $book = Book::with([
            'authors', 
            'category', 
            'reviews' => function($query) {
                $query->with('user')->latest();
            }
        ])->findOrFail($id);

        // Calcular estadísticas de rating
        $reviews = $book->reviews;
        $book->average_rating = $reviews->avg('rating') ?? 0;
        $book->reviews_count = $reviews->count();

        // Verificar si el usuario puede hacer review
        $canReview = false;
        $hasReviewed = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            $canReview = $user->canReviewBook($book->book_id);
            $hasReviewed = $user->hasReviewedBook($book->book_id);
        }

        return view('books.show', compact('book', 'canReview', 'hasReviewed'));
    }

    /**
     * Buscar libros
     */
    public function buscar(Request $request)
    {
        $query = $request->input('q');
        
        $books = Book::with(['authors', 'category'])
            ->where(function($q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                  ->orWhere('subtitle', 'ILIKE', "%{$query}%")
                  ->orWhereHas('authors', function($author) use ($query) {
                      $author->where('first_name', 'ILIKE', "%{$query}%")
                            ->orWhere('last_name', 'ILIKE', "%{$query}%");
                  });
            })
            ->paginate(20);

        return view('books.search', compact('books', 'query'));
    }

    /**
     * Libros destacados
     */
    public function featured()
    {
        $books = Book::with(['authors', 'category'])
            ->where('status', 'En stock')
            ->whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 0)
            ->orderBy('discount_percentage', 'desc')
            ->take(12)
            ->get();

        return view('books.featured', compact('books'));
    }
}