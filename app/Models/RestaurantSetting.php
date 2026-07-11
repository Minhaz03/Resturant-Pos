<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'name', 'slug', 'logo', 'tagline', 'address', 'phone', 'email', 'website',
        'currency', 'currency_symbol', 'tax_rate', 'tax_name', 'timezone',
        'date_format', 'time_format', 'receipt_footer', 'invoice_prefix',
        'order_prefix', 'loyalty_enabled', 'loyalty_rate',
        'mail_from_name', 'mail_from_address',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'loyalty_rate' => 'decimal:2',
        'loyalty_enabled' => 'boolean',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::first();
        return $setting ? ($setting->$key ?? $default) : $default;
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/logo.png');
    }
}

