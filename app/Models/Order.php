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
const STATUS_PENDING = 'pendiente';
const STATUS_PENDIENTE = 'pendiente'; 
const STATUS_PROCESSING = 'procesando';
const STATUS_PROCESANDO = 'procesando'; 
const STATUS_CONFIRMED = 'confirmado';
const STATUS_PAGADO = 'pagado';
const STATUS_SHIPPED = 'enviado';
const STATUS_ENVIADO = 'enviado'; 
const STATUS_DELIVERED = 'entregado';
const STATUS_ENTREGADO = 'entregado'; 
const STATUS_CANCELLED = 'cancelado';
const STATUS_CANCELADO = 'cancelado'; 

// CONSTANTES DE PAGO
const PAYMENT_STATUS_PENDING = 'pendiente';
const PAYMENT_STATUS_COMPLETED = 'completado';
const PAYMENT_STATUS_FAILED = 'fallido';
const PAYMENT_STATUS_REFUNDED = 'reembolsado';

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
                self::STATUS_PENDIENTE => 'Pendiente',
                self::STATUS_PAGADO => 'Pagado',
                self::STATUS_PROCESANDO => 'En proceso',
                self::STATUS_ENVIADO => 'Enviado',
                self::STATUS_ENTREGADO => 'Entregado',
                self::STATUS_CANCELADO => 'Cancelado',
                default => 'Desconocido',
            };
        }

        public function getStatusColorAttribute()
        {
            return match($this->status) {
                self::STATUS_PENDIENTE => 'warning',
                self::STATUS_PAGADO => 'info',
                self::STATUS_PROCESANDO => 'primary',
                self::STATUS_ENVIADO => 'secondary',
                self::STATUS_ENTREGADO => 'success',
                self::STATUS_CANCELADO => 'danger',
                default => 'dark',
            };
        }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, [
            self::STATUS_PENDIENTE,
            self::STATUS_PAGADO,
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
        return $query->where('status', self::STATUS_PENDIENTE);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAGADO);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESANDO);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_ENVIADO);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_ENTREGADO);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELADO);
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
            'status' => self::STATUS_PAGADO,
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
            'status' => self::STATUS_ENVIADO,
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
            'status' => self::STATUS_ENTREGADO,
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
            'status' => self::STATUS_CANCELADO,
            'cancelled_at' => Carbon::now(),
            'cancellation_reason' => $reason,
        ]);

        // Restaurar inventario si ya se había reducido
        if (in_array($this->status, [self::STATUS_PAGADO, self::STATUS_PROCESANDO])) {
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