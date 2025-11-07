<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'address_id',
        'shipping_method_id',
        'coupon_id',
        'notes',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'can_be_cancelled',
        'total_items',
    ];

    // Estados posibles
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // ==================== RELACIONES ====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'coupon_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id', 'order_id');
    }

    // ==================== ACCESSORS ====================
    
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PAID => 'Pagado',
            self::STATUS_PROCESSING => 'En proceso',
            self::STATUS_SHIPPED => 'Enviado',
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_CANCELLED => 'Cancelado',
            default => 'Desconocido',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_SHIPPED => 'secondary',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'dark',
        };
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PAID,
        ]);
    }

    public function getTotalItemsAttribute()
    {
        return $this->items()->sum('quantity');
    }

    // ==================== SCOPES ====================
    
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    /**
     * Generar número de orden único
     */
    public static function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . strtoupper(substr(uniqid(), -8));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Calcular totales de la orden
     */
    public function calculateTotals()
    {
        $subtotal = $this->items()->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->subtotal = $subtotal;

        // Calcular descuento si hay cupón
        if ($this->coupon_id && $this->coupon) {
            $this->discount_amount = $this->coupon->calculateDiscount($subtotal);
        }

        // Calcular costo de envío
        if ($this->shipping_method_id && $this->shippingMethod) {
            $totalWeight = $this->items()->sum(function ($item) {
                return ($item->book->weight ?? 0) * $item->quantity;
            }) / 1000; // Convertir gramos a kg

            $this->shipping_cost = $this->shippingMethod->calculateShippingCost(
                $totalWeight,
                $subtotal - $this->discount_amount
            );
        }

        // Calcular impuestos (16% en México)
        $taxableAmount = $subtotal - $this->discount_amount;
        $this->tax_amount = round($taxableAmount * 0.16, 2);

        // Total final
        $this->total = $subtotal - $this->discount_amount + $this->shipping_cost + $this->tax_amount;

        $this->save();
    }

    /**
     * Marcar como pagado
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_status' => 'completed',
        ]);

        // Reducir inventario y actualizar ventas
        foreach ($this->items as $item) {
            $item->book->incrementSales($item->quantity);
        }

        // Registrar uso del cupón si aplica
        if ($this->coupon_id && $this->discount_amount > 0) {
            $this->coupon->recordUsage(
                $this->user_id,
                $this->order_id,
                $this->discount_amount
            );
        }
    }

    /**
     * Marcar como enviado
     */
    public function markAsShipped($trackingNumber = null)
    {
        $this->update([
            'status' => self::STATUS_SHIPPED,
            'tracking_number' => $trackingNumber,
            'shipped_at' => Carbon::now(),
        ]);
    }

    /**
     * Marcar como entregado
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => Carbon::now(),
        ]);
    }

    /**
     * Cancelar orden
     */
    public function cancel($reason = null)
    {
        if (!$this->can_be_cancelled) {
            throw new \Exception('Esta orden no puede ser cancelada');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => Carbon::now(),
            'cancellation_reason' => $reason,
        ]);

        // Restaurar inventario si ya se había reducido
        if (in_array($this->status, [self::STATUS_PAID, self::STATUS_PROCESSING])) {
            foreach ($this->items as $item) {
                $item->book->increment('stock_quantity', $item->quantity);
                $item->book->decrement('sales_count', $item->quantity);
            }
        }
    }

    /**
     * Obtener resumen de la orden
     */
    public function getSummary()
    {
        return [
            'order_number' => $this->order_number,
            'status' => $this->status_label,
            'total_items' => $this->total_items,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount_amount,
            'shipping' => $this->shipping_cost,
            'tax' => $this->tax_amount,
            'total' => $this->total,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}