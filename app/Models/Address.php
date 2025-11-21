<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'street_address',
        'apartment',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'references',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $appends = [
        'full_address',
        'formatted_address',
    ];

    // ==================== RELACIONES ====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id', 'address_id');
    }

    // ==================== ACCESSORS ====================
    
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street_address,
            $this->apartment,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedAddressAttribute()
    {
        return [
            'line1' => $this->street_address . ($this->apartment ? ', ' . $this->apartment : ''),
            'line2' => $this->neighborhood,
            'line3' => $this->city . ', ' . $this->state . ' ' . $this->postal_code,
            'line4' => $this->country,
        ];
    }

    // ==================== SCOPES ====================
    
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== MÉTODOS ÚTILES ====================
    
    public function setAsDefault()
    {
        static::where('user_id', $this->user_id)
              ->where('address_id', '!=', $this->address_id)
              ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    public function isComplete()
    {
        return !empty($this->recipient_name) 
            && !empty($this->phone)
            && !empty($this->street_address)
            && !empty($this->city)
            && !empty($this->state)
            && !empty($this->postal_code);
    }
}