<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MenuItem extends Model implements HasMedia
{
    use \App\Traits\BelongsToTenant;
    use SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'barcode', 'description',
        'price', 'cost_price', 'discount', 'tax_rate', 'image',
        'is_available', 'is_featured', 'status', 'prep_time', 'unit', 'sort_order',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'cost_price'   => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax_rate'     => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured'  => 'boolean',
        'status'       => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(200)
             ->height(200)
             ->nonQueued();
    }

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

    public function ingredients()
    {
        return $this->hasMany(MenuItemIngredient::class);
    }

    public function getImageUrlAttribute(): string
    {
        // First check Spatie Media Library
        if ($this->hasMedia('image')) {
            $url = $this->getFirstMediaUrl('image');
            if (app()->environment('local') || request()->getHost() === '127.0.0.1' || request()->getHost() === 'localhost') {
                return str_replace('https://your-app.up.railway.app', request()->getSchemeAndHttpHost(), $url);
            }
            return $url;
        }
        // Fallback to legacy image column
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-food.png');
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

