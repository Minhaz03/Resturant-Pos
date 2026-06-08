<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenOrder extends Model
{
    protected $fillable = [
        'order_id', 'order_item_id', 'station', 'status', 'priority',
        'estimated_time', 'started_at', 'completed_at', 'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function getElapsedTimeAttribute()
    {
        if (!$this->started_at) return 0;
        return now()->diffInMinutes($this->started_at);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'preparing'])->orderBy('priority', 'desc')->orderBy('created_at');
    }
}
