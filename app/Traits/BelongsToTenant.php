<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (app()->has('tenant')) {
                $model->tenant_id = app('tenant')->id;
            } elseif (\Illuminate\Support\Facades\Session::has('tenant_id')) {
                $model->tenant_id = \Illuminate\Support\Facades\Session::get('tenant_id');
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
