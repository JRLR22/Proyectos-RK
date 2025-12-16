<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
        'review_date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    /**
     * Usuario que escribió la reseña
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Libro reseñado
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Nombre del autor de la reseña
     */
    public function getAuthorNameAttribute()
    {
        return $this->user->full_name ?? 'Usuario Anónimo';
    }

    /**
     * Rating en formato de estrellas
     */
    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Reseñas por calificación
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope: Reseñas verificadas (usuario compró el libro)
     */
    public function scopeVerified($query)
    {
        return $query->whereHas('user', function($q) {
            $q->whereHas('orders', function($order) {
                $order->whereHas('items', function($item) {
                    $item->where('book_id', $this->book_id);
                })->whereIn('status', ['entregado']);
            });
        });
    }

    /**
     * Scope: Reseñas recientes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Reseñas con comentarios
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }

    // ==================== MÉTODOS ÚTILES ====================

    /**
     * Verificar si es una compra verificada
     */
    public function isVerifiedPurchase()
    {
        return $this->user->canReviewBook($this->book_id);
    }
}