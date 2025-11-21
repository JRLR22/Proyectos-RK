<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'price',
        'discount_percentage',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected $appends = [
        'total',
        'discount_amount',
    ];

    // ==================== RELACIONES ====================
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Total del item (precio × cantidad)
     */
    public function getTotalAttribute()
    {
        return round($this->price * $this->quantity, 2);
    }

    /**
     * Monto del descuento aplicado
     */
    public function getDiscountAmountAttribute()
    {
        if ($this->discount_percentage > 0) {
            return round(($this->total * $this->discount_percentage) / 100, 2);
        }
        return 0;
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    /**
     * Calcular subtotal (con descuento si aplica)
     */
    public function calculateSubtotal()
    {
        $total = $this->price * $this->quantity;
        
        if ($this->discount_percentage > 0) {
            $discount = ($total * $this->discount_percentage) / 100;
            $total -= $discount;
        }

        $this->subtotal = round($total, 2);
        $this->save();

        return $this->subtotal;
    }

    /**
     * Crear item desde un libro
     */
    public static function createFromBook(Book $book, int $quantity)
    {
        return new self([
            'book_id' => $book->book_id,
            'quantity' => $quantity,
            'price' => $book->price,
            'discount_percentage' => $book->discount_percentage ?? 0,
        ]);
    }

    /**
     * Verificar si hay stock suficiente
     */
    public function hasEnoughStock()
    {
        return $this->book && $this->book->stock_quantity >= $this->quantity;
    }

    /**
     * Obtener resumen del item
     */
    public function getSummary()
    {
        return [
            'book_title' => $this->book->title ?? 'Libro no disponible',
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
        ];
    }
}