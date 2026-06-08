<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MenuItem extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'barcode', 'description',
        'price', 'cost_price', 'discount', 'tax_rate', 'image',
        'is_available', 'is_featured', 'status', 'prep_time', 'unit', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-food.png');
    }

    public function getEffectivePriceAttribute()
    {
        if ($this->discount > 0) {
            return round($this->price - ($this->price * $this->discount / 100), 2);
        }
        return $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->where('is_available', true);
    }
}
