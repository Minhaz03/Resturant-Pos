<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'order_id', 'rider_id', 'delivery_address', 'delivery_phone',
        'delivery_charge', 'distance_km', 'status', 'notes',
        'tracking_code', 'assigned_at', 'picked_up_at', 'delivered_at',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'assigned' => 'info',
            'picked_up' => 'primary',
            'on_way' => 'primary',
            'delivered' => 'success',
            'failed', 'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}

