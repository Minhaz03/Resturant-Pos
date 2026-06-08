<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = ['day_of_week', 'day_name', 'is_open', 'open_time', 'close_time'];

    protected $casts = ['is_open' => 'boolean'];

    public static function isOpenNow(): bool
    {
        $today = now()->dayOfWeek;
        $hour = static::where('day_of_week', $today)->first();
        if (!$hour || !$hour->is_open) return false;
        $currentTime = now()->format('H:i:s');
        return $currentTime >= $hour->open_time && $currentTime <= $hour->close_time;
    }
}
