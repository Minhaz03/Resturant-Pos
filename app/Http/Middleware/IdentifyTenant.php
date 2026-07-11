<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tenant_id) {
            $tenant = \App\Models\Tenant::find(auth()->user()->tenant_id);
            
            if ($tenant && $tenant->is_active) {
                app()->instance('tenant', $tenant);
                \Illuminate\Support\Facades\Session::put('tenant_id', $tenant->id);
            }
        }

        return $next($request);
    }
}
