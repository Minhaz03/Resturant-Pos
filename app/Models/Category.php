<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use \App\Traits\BelongsToTenant;
    use SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'description', 'image', 'sort_order', 'status'];

    protected $casts = ['status' => 'boolean'];

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

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function activeMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('status', true)->where('is_available', true);
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
        return asset('images/default-category.png');
    }
}

