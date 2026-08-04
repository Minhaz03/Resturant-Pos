<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use \App\Traits\BelongsToTenant;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['tenant_id', 'name', 'description'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
