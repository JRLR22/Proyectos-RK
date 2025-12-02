<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_item_id';

    protected $fillable = [
        'cart_id',
        'book_id',
        'quantity',
        'price_at_addition',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_addition' => 'decimal:2',
    ];

    /**
     * Relación con el carrito
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    /**
     * Relación con el libro
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    /**
     * Calcular subtotal del item
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price_at_addition;
    }

    /**
     * Incrementar cantidad
     */
    public function incrementQuantity($amount = 1)
    {
        $this->quantity += $amount;
        $this->save();
        return $this;
    }

    /**
     * Actualizar cantidad
     */
    public function updateQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->save();
        return $this;
    }
}