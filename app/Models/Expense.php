<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use \App\Traits\BelongsToTenant;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['tenant_id', 'expense_category_id', 'amount', 'date', 'reference_number', 'note', 'user_id'];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
