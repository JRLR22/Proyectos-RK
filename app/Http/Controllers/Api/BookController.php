<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Listar todos los libros (API para móvil)
     * GET /api/books
     */
    public function index()
    {
        try {
            // 👇 AGREGAR 'reviews' para que cargue las reseñas
            $books = Book::with(['authors', 'category', 'reviews'])
                ->get()
                ->map(function($book) {
                    return [
                        'book_id' => $book->book_id,
                        'isbn' => $book->isbn,
                        'title' => $book->title,
                        'subtitle' => $book->subtitle,
                        'publisher' => $book->publisher,
                        'publication_year' => $book->publication_year,
                        'price' => (float) $book->price,
                        'stock_quantity' => $book->stock_quantity,
                        'cover_image' => $book->cover_image,
                        'category_id' => $book->category_id,
                        'category_name' => $book->category->name ?? null,
                        'authors' => $book->authors_list,
                        'description' => $book->description,
                        'pages' => $book->pages,
                        'average_rating' => round($book->average_rating, 1), 
                        'reviews_count' => $book->reviews_count, 
                    ];
                });

            return response()->json($books);

        } catch (\Exception $e) {
            \Log::error('Error en Api\BookController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar libros',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un libro individual (API para móvil)
     * GET /api/books/{id}
     */
    public function show($id)
    {
        try {
            $book = Book::with(['authors', 'category', 'reviews.user'])
                ->findOrFail($id);

            return response()->json([
                'book_id' => $book->book_id,
                'isbn' => $book->isbn,
                'title' => $book->title,
                'subtitle' => $book->subtitle,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'price' => (float) $book->price,
                'stock_quantity' => $book->stock_quantity,
                'cover_image' => $book->cover_image,
                'category_id' => $book->category_id,
                'category_name' => $book->category->name ?? null,
                'authors' => $book->authors_list,
                'description' => $book->description,
                'pages' => $book->pages,
                'average_rating' => round($book->average_rating, 1),
                'reviews_count' => $book->reviews_count,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Libro no encontrado',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Obtener detalle completo de un libro con estadísticas de reseñas
     * GET /api/books/{id}/detail
     */
    public function detail($id)
    {
        try {
            $book = Book::with(['authors', 'category', 'reviews.user'])
                ->findOrFail($id);

            $reviews = $book->reviews;
            
            return response()->json([
                'book' => [
                    'book_id' => $book->book_id,
                    'isbn' => $book->isbn,
                    'title' => $book->title,
                    'subtitle' => $book->subtitle,
                    'publisher' => $book->publisher,
                    'publication_year' => $book->publication_year,
                    'price' => (float) $book->price,
                    'stock_quantity' => $book->stock_quantity,
                    'cover_image' => $book->cover_image,
                    'category_id' => $book->category_id,
                    'category_name' => $book->category->name ?? null,
                    'authors' => $book->authors_list,
                    'description' => $book->description,
                    'pages' => $book->pages,
                ],
                'reviews_stats' => [
                    'average_rating' => round($reviews->avg('rating') ?? 0, 1),
                    'total_reviews' => $reviews->count(),
                    'rating_distribution' => [
                        '5' => $reviews->where('rating', 5)->count(),
                        '4' => $reviews->where('rating', 4)->count(),
                        '3' => $reviews->where('rating', 3)->count(),
                        '2' => $reviews->where('rating', 2)->count(),
                        '1' => $reviews->where('rating', 1)->count(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Libro no encontrado',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Buscar libros (API)
     * GET /api/books/search
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        $books = Book::with(['authors', 'category', 'reviews'])
            ->where(function($q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                  ->orWhere('subtitle', 'ILIKE', "%{$query}%");
            })
            ->get()
            ->map(function($book) {
                return [
                    'book_id' => $book->book_id,
                    'title' => $book->title,
                    'authors' => $book->authors_list,
                    'price' => (float) $book->price,
                    'cover_image' => $book->cover_image,
                    'average_rating' => round($book->average_rating, 1),
                    'reviews_count' => $book->reviews_count,
                ];
            });

        return response()->json($books);
    }

    /**
     * Libros destacados (API)
     * GET /api/books/featured
     */
    public function featured()
    {
        $books = Book::with(['authors', 'category', 'reviews'])
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get()
            ->map(function($book) {
                return [
                    'book_id' => $book->book_id,
                    'title' => $book->title,
                    'authors' => $book->authors_list,
                    'price' => (float) $book->price,
                    'cover_image' => $book->cover_image,
                    'average_rating' => round($book->average_rating, 1),
                    'reviews_count' => $book->reviews_count,
                ];
            });

        return response()->json($books);
    }
}