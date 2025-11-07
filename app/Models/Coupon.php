<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $primaryKey = 'coupon_id';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_uses' => 'integer',
        'max_uses_per_user' => 'integer',
        'used_count' => 'integer',
        'active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = [
        'is_valid',
        'is_expired',
        'discount_text',
    ];

    // ==================== RELACIONES ====================
    
    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id', 'coupon_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_usage', 'coupon_id', 'user_id')
                    ->withPivot('discount_applied', 'used_at');
    }

    // ==================== ACCESSORS ====================
    
    public function getIsValidAttribute()
    {
        if (!$this->active) return false;

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;

        return true;
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->expires_at) return false;
        return Carbon::now()->gt($this->expires_at);
    }

    public function getDiscountTextAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '% de descuento';
        }
        return '$' . number_format($this->discount_value, 2) . ' de descuento';
    }

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('active', true)
                     ->where(function ($q) use ($now) {
                         $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                     })
                     ->where(function ($q) use ($now) {
                         $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                     })
                     ->where(function ($q) {
                         $q->whereNull('max_uses')->orWhereRaw('used_count < max_uses');
                     });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_purchase) return 0;

        if ($this->discount_type === 'percentage') {
            return round(($subtotal * $this->discount_value) / 100, 2);
        }

        return min($this->discount_value, $subtotal);
    }

    public function canBeUsedBy($userId, $subtotal = 0)
    {
        if (!$this->is_valid) {
            return ['valid' => false, 'message' => 'El cupón no es válido o ha expirado'];
        }

        if ($subtotal > 0 && $subtotal < $this->min_purchase) {
            return [
                'valid' => false, 
                'message' => 'La compra mínima es de $' . number_format($this->min_purchase, 2)
            ];
        }

        $userUsages = $this->usages()->where('user_id', $userId)->count();
        
        if ($userUsages >= $this->max_uses_per_user) {
            return [
                'valid' => false, 
                'message' => 'Ya has usado este cupón el máximo de veces permitido'
            ];
        }

        return ['valid' => true, 'discount' => $this->calculateDiscount($subtotal)];
    }

    public function recordUsage($userId, $orderId, $discountApplied)
    {
        CouponUsage::create([
            'coupon_id' => $this->coupon_id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_applied' => $discountApplied,
            'used_at' => Carbon::now(),
        ]);

        $this->increment('used_count');
    }
}