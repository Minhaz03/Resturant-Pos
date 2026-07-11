<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'icon', 'color',
        'action_url', 'is_read', 'read_at', 'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}

