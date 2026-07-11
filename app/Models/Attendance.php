<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Traits\BelongsToTenant;
    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'working_hours', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'working_hours' => 'decimal:2',
    ];

    public function getCheckInAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getCheckOutAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

