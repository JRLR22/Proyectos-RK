<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'shipping_method_id';

    protected $fillable = [
        'name',
        'description',
        'base_cost',
        'cost_per_kg',
        'free_shipping_threshold',
        'estimated_days_min',
        'estimated_days_max',
        'available_states',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'base_cost' => 'decimal:2',
        'cost_per_kg' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'estimated_days_min' => 'integer',
        'estimated_days_max' => 'integer',
        'available_states' => 'array',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'estimated_delivery',
        'is_free_shipping_available',
    ];

    // ==================== RELACIONES ====================
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_method_id', 'shipping_method_id');
    }

    // ==================== ACCESSORS ====================
    
    public function getEstimatedDeliveryAttribute()
    {
        if ($this->estimated_days_min === $this->estimated_days_max) {
            return $this->estimated_days_min . ' días hábiles';
        }
        return $this->estimated_days_min . '-' . $this->estimated_days_max . ' días hábiles';
    }

    public function getIsFreeShippingAvailableAttribute()
    {
        return $this->free_shipping_threshold !== null;
    }

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function scopeAvailableForState($query, $state)
    {
        return $query->where('active', true)
                     ->where(function ($q) use ($state) {
                         $q->whereNull('available_states')
                           ->orWhereJsonContains('available_states', $state);
                     });
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    public function calculateShippingCost($weightKg, $subtotal = 0)
    {
        if ($this->free_shipping_threshold && $subtotal >= $this->free_shipping_threshold) {
            return 0;
        }

        $cost = $this->base_cost + ($weightKg * $this->cost_per_kg);
        return round($cost, 2);
    }

    public function isAvailableForState($state)
    {
        if (!$this->active) return false;
        if (empty($this->available_states)) return true;
        return in_array($state, $this->available_states);
    }

    public function getFreeShippingMessage($currentSubtotal)
    {
        if (!$this->free_shipping_threshold) return null;

        if ($currentSubtotal >= $this->free_shipping_threshold) {
            return '¡Envío GRATIS!';
        }

        $remaining = $this->free_shipping_threshold - $currentSubtotal;
        return 'Agrega $' . number_format($remaining, 2) . ' más para envío gratis';
    }
}