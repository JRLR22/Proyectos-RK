<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'payment_method',
        'status',
        'amount',
        'currency',
        'stripe_payment_intent_id',
        'paypal_order_id',
        'payment_details',
        'error_message',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Estados
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_CANCELLED = 'cancelled';

    // Métodos de pago
    const METHOD_STRIPE = 'stripe';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_CASH = 'cash_on_delivery';

    // ==================== RELACIONES ====================

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // ==================== MÉTODOS DE ESTADO ====================

    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsRefunded()
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'refunded_at' => now(),
        ]);
    }

    // ==================== SCOPES ====================

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }
}