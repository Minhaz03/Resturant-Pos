<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use \App\Traits\BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'address', 'dob', 'gender',
        'loyalty_points', 'total_spent', 'total_orders', 'status', 'notes',
    ];

    protected $casts = [
        'dob' => 'date',
        'total_spent' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function addLoyaltyPoints(int $points, $orderId = null, string $description = '')
    {
        $this->increment('loyalty_points', $points);
        $this->loyaltyTransactions()->create([
            'order_id' => $orderId,
            'type' => 'earn',
            'points' => $points,
            'balance_after' => $this->fresh()->loyalty_points,
            'description' => $description,
        ]);
    }

    public function redeemLoyaltyPoints(int $points, $orderId = null)
    {
        if ($this->loyalty_points < $points) {
            return false;
        }
        $this->decrement('loyalty_points', $points);
        $this->loyaltyTransactions()->create([
            'order_id' => $orderId,
            'type' => 'redeem',
            'points' => -$points,
            'balance_after' => $this->fresh()->loyalty_points,
            'description' => 'Points redeemed for order',
        ]);
        return true;
    }
}

