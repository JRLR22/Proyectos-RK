<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
        'discount_amount',
        'expires_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con los items del carrito
     */
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }

    /**
     * Calcular subtotal del carrito
     */
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price_at_addition;
        });
    }

    /**
     * Calcular total con descuento
     */
    public function getTotalAttribute()
    {
        return max($this->subtotal - $this->discount_amount, 0);
    }

    /**
     * Contar items totales
     */
    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Peso total del carrito
     */
    public function getTotalWeightAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->book->weight ?? 0) * $item->quantity;
        });
    }

    /**
     * Limpiar carrito
     */
    public function clear()
    {
        $this->items()->delete();
        $this->coupon_code = null;
        $this->discount_amount = 0;
        $this->save();
    }

    /**
     * Aplicar cupón
     */
    public function applyCoupon(Coupon $coupon, $discountAmount)
    {
        $this->coupon_code = $coupon->code;
        $this->discount_amount = $discountAmount;
        $this->save();
    }

    /**
     * Remover cupón
     */
    public function removeCoupon()
    {
        $this->coupon_code = null;
        $this->discount_amount = 0;
        $this->save();
    }

    /**
     * Scope: Carritos activos (no expirados)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope: Carritos de usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Carritos de sesión (guest)
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
}