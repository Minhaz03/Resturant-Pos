<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['table_number', 'name', 'capacity', 'location', 'status', 'qr_code'];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function activeOrders()
    {
        return $this->belongsToMany(Order::class)->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'served']);
    }

    public function getActiveOrderAttribute()
    {
        return $this->activeOrders->first();
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
