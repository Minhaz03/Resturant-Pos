<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    use \App\Traits\BelongsToTenant;
    use HasFactory, Notifiable, HasRoles, CausesActivity;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar', 'status', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}

