<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'order_id',
        'invoice_number',
        'subtotal',
        'tax',
        'total',
        'issue_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'issue_date' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    /**
     * Orden asociada a la factura
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Obtener nombre del cliente
     */
    public function getCustomerNameAttribute()
    {
        return $this->order->user->full_name ?? 'Sin nombre';
    }

    /**
     * Obtener email del cliente
     */
    public function getCustomerEmailAttribute()
    {
        return $this->order->user->email ?? '';
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Facturas del usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('order', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Scope: Facturas por rango de fechas
     */
    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('issue_date', [$from, $to]);
    }

    /**
     * Scope: Facturas recientes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('issue_date', 'desc');
    }
}