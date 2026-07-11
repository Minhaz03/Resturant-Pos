<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'order_id', 'payment_number', 'amount', 'method', 'status',
        'transaction_id', 'reference', 'change_amount', 'split_details',
        'processed_by', 'paid_at', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'split_details' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

