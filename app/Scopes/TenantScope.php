<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->has('tenant')) {
            $tenant = app('tenant');
            $builder->where($model->getTable() . '.tenant_id', $tenant->id);
        } elseif (Session::has('tenant_id')) {
            $builder->where($model->getTable() . '.tenant_id', Session::get('tenant_id'));
        }
    }
}
