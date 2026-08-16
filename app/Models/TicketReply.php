<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Storage;

class TicketReply extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'admin_id',
        'message',
        'attachment_path'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getAttachmentUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('attachments');
        if ($mediaUrl) {
            return $mediaUrl;
        }
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }
}
