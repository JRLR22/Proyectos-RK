<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Obtener reseñas de un libro
     * GET /api/books/{book_id}/reviews
     */
    public function index($bookId)
    {
        $book = Book::findOrFail($bookId);

        $reviews = Review::with('user')
                         ->where('book_id', $bookId)
                         ->orderBy('created_at', 'desc')
                         ->get();

        // Calcular estadísticas
        $stats = [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating') ?? 0,
            'rating_distribution' => [
                '5' => $reviews->where('rating', 5)->count(),
                '4' => $reviews->where('rating', 4)->count(),
                '3' => $reviews->where('rating', 3)->count(),
                '2' => $reviews->where('rating', 2)->count(),
                '1' => $reviews->where('rating', 1)->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'stats' => [
                'total_reviews' => $stats['total_reviews'],
                'average_rating' => round($stats['average_rating'], 1),
                'rating_distribution' => $stats['rating_distribution'],
            ],
            'reviews' => $reviews->map(function ($review) {
                return [
                    'review_id' => $review->review_id,
                    'user' => [
                        'user_id' => $review->user->user_id,
                        'name' => $review->user->full_name,
                    ],
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d/m/Y'),
                    'is_verified_purchase' => $review->user->canReviewBook($review->book_id),
                ];
            }),
        ]);
    }

    /**
     * Crear nueva reseña
     * POST /api/books/{book_id}/reviews
     */
    public function store(Request $request, $bookId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        // Verificar si ya ha dejado una reseña
        $existingReview = Review::where('user_id', $user->user_id)
                                ->where('book_id', $bookId)
                                ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has dejado una reseña para este libro. Puedes editarla.',
            ], 400);
        }

        // Verificar si ha comprado el libro
        $canReview = $user->canReviewBook($bookId);

        if (!$canReview) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes reseñar libros que hayas comprado',
            ], 403);
        }

        // Crear reseña
        $review = Review::create([
            'user_id' => $user->user_id,
            'book_id' => $bookId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reseña publicada exitosamente',
            'review' => [
                'review_id' => $review->review_id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('d/m/Y'),
            ],
        ], 201);
    }

    /**
     * Actualizar reseña
     * PUT /api/reviews/{id}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|required|string|min:10|max:1000',
        ]);

        $user = Auth::user();

        $review = Review::where('user_id', $user->user_id)
                        ->findOrFail($id);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reseña actualizada exitosamente',
            'review' => [
                'review_id' => $review->review_id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'updated_at' => $review->updated_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Eliminar reseña
     * DELETE /api/reviews/{id}
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $review = Review::where('user_id', $user->user_id)
                        ->findOrFail($id);

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reseña eliminada exitosamente',
        ]);
    }

    /**
     * Obtener reseña del usuario para un libro específico
     * GET /api/books/{book_id}/reviews/my-review
     */
    public function myReview($bookId)
    {
        $user = Auth::user();

        $review = Review::where('user_id', $user->user_id)
                        ->where('book_id', $bookId)
                        ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'No has dejado una reseña para este libro',
                'can_review' => $user->canReviewBook($bookId),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'review' => [
                'review_id' => $review->review_id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('d/m/Y'),
                'updated_at' => $review->updated_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Obtener todas las reseñas del usuario
     * GET /api/reviews/my-reviews
     */
    public function myReviews()
    {
        $user = Auth::user();

        $reviews = Review::with('book')
                         ->where('user_id', $user->user_id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'reviews' => $reviews->map(function ($review) {
                return [
                    'review_id' => $review->review_id,
                    'book' => [
                        'book_id' => $review->book->book_id,
                        'title' => $review->book->title,
                        'cover_url' => $review->book->cover_url,
                    ],
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d/m/Y'),
                ];
            }),
            'total_reviews' => $reviews->count(),
        ]);
    }

    /**
     * Verificar si el usuario puede reseñar un libro
     * GET /api/books/{book_id}/reviews/can-review
     */
    public function canReview($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $canReview = $user->canReviewBook($bookId);
        $hasReviewed = $user->hasReviewedBook($bookId);

        return response()->json([
            'success' => true,
            'can_review' => $canReview && !$hasReviewed,
            'has_reviewed' => $hasReviewed,
            'message' => $this->getReviewMessage($canReview, $hasReviewed),
        ]);
    }

    /**
     * Obtener mensaje apropiado sobre el estado de reseña
     */
    private function getReviewMessage($canReview, $hasReviewed)
    {
        if ($hasReviewed) {
            return 'Ya has dejado una reseña para este libro';
        }

        if (!$canReview) {
            return 'Solo puedes reseñar libros que hayas comprado';
        }

        return 'Puedes dejar una reseña para este libro';
    }
}