<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'code', 'name', 'type', 'value', 'min_order_amount', 'max_discount',
        'usage_limit', 'used_count', 'per_user_limit', 'start_date',
        'end_date', 'status', 'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->status) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->start_date && $this->start_date->isFuture()) return false;
        if ($this->end_date && $this->end_date->isPast()) return false;
        return true;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($orderAmount < $this->min_order_amount) return 0;

        $discount = $this->type === 'percentage'
            ? ($orderAmount * $this->value / 100)
            : $this->value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return round(min($discount, $orderAmount), 2);
    }
}

