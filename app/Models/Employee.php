<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Employee extends Model implements HasMedia
{
    use \App\Traits\BelongsToTenant;
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'employee_id', 'name', 'phone', 'email', 'address',
        'dob', 'date_of_birth', 'gender', 'role', 'department', 'salary', 'hire_date',
        'termination_date', 'avatar', 'nid', 'nid_photo', 'emergency_contact',
        'bank_account', 'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function getDateOfBirthAttribute()
    {
        return $this->dob;
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['dob'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAvatarUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('avatars');
        if ($mediaUrl) return $mediaUrl;
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    public function getNidPhotoUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('nid_photos');
        if ($mediaUrl) return $mediaUrl;
        return $this->nid_photo ? asset('storage/' . $this->nid_photo) : null;
    }

    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }
}

