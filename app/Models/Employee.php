<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use \App\Traits\BelongsToTenant;
    use SoftDeletes;

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
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }
}

