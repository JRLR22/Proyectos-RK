<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $timestamps = true;
    
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $appends = [
        'full_name',
    ];

    // ==================== AUTH ====================
    
    /**
     * Laravel recibe 'password', pero la tabla usa 'password_hash'
     */
/**
 * Get the password for the user.
 *
 * @return string
 */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

        // NUEVO: Agregar esto también
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }


    // ==================== RELACIONES ====================
    
    /**
     * Órdenes del usuario
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    /**
     * Direcciones de envío del usuario
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', 'user_id');
    }

    /**
     * Lista de deseos del usuario
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'user_id');
    }

    /**
     * Reseñas escritas por el usuario
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    /**
     * Cupones usados por el usuario
     */
    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class, 'user_id', 'user_id');
    }

    /**
     * Cupones que el usuario ha usado (relación many-to-many)
     */
    public function usedCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_usage', 'user_id', 'coupon_id')
                    ->withPivot('discount_applied', 'used_at');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    /**
     * Obtener dirección predeterminada
     */
    public function getDefaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    /**
     * Verificar si tiene direcciones guardadas
     */
    public function hasAddresses()
    {
        return $this->addresses()->exists();
    }

    /**
     * Obtener todos los libros en wishlist
     */
    public function getWishlistBooks()
    {
        return $this->wishlist()->with('book')->get()->pluck('book');
    }

    /**
     * Verificar si un libro está en wishlist
     */
    public function hasInWishlist($bookId)
    {
        return $this->wishlist()->where('book_id', $bookId)->exists();
    }

    /**
     * Agregar libro a wishlist
     */
    public function addToWishlist($bookId)
    {
        if (!$this->hasInWishlist($bookId)) {
            return $this->wishlist()->create(['book_id' => $bookId]);
        }
        return null;
    }

    /**
     * Remover libro de wishlist
     */
    public function removeFromWishlist($bookId)
    {
        return $this->wishlist()->where('book_id', $bookId)->delete();
    }

    /**
     * Obtener órdenes recientes
     */
    public function getRecentOrders($limit = 10)
    {
        return $this->orders()
                    ->with(['items.book', 'address', 'shippingMethod'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Calcular total gastado
     */
    public function getTotalSpent()
    {
        return $this->orders()
                    ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_DELIVERED])
                    ->sum('total');
    }

    /**
     * Contar órdenes completadas
     */
    public function getCompletedOrdersCount()
    {
        return $this->orders()
                    ->whereIn('status', [Order::STATUS_DELIVERED])
                    ->count();
    }

    /**
     * Verificar si puede usar un cupón
     */
    public function canUseCoupon($couponCode, $subtotal = 0)
    {
        $coupon = Coupon::byCode($couponCode)->valid()->first();
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Cupón inválido o expirado',
            ];
        }

        return $coupon->canBeUsedBy($this->user_id, $subtotal);
    }

    /**
     * Verificar si ya ha revisado un libro
     */
    public function hasReviewedBook($bookId)
    {
        return $this->reviews()->where('book_id', $bookId)->exists();
    }

    /**
     * Verificar si puede revisar un libro (debe haberlo comprado)
     */
    public function canReviewBook($bookId)
    {
        return $this->orders()
                    ->whereHas('items', function ($query) use ($bookId) {
                        $query->where('book_id', $bookId);
                    })
                    ->whereIn('status', [Order::STATUS_DELIVERED])
                    ->exists();
    }
}