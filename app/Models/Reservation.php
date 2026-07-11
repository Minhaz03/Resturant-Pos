<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'reservation_number', 'customer_id', 'customer_name',
        'customer_phone', 'customer_email', 'reservation_date', 'reservation_time',
        'guest_count', 'status', 'notes', 'confirmed_at', 'created_by', 'deposit_amount',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'confirmed_at' => 'datetime',
        'deposit_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed']);
    }
}

