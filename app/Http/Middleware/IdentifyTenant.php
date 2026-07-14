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
        if (auth()->check()) {
            $user = auth()->user();
            
            // Allow super admins to bypass tenant verification check
            if ($user->hasRole('super_admin')) {
                return $next($request);
            }
            
            if (!$user->tenant_id) {
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is not associated with any restaurant tenant.'
                ]);
            }
            
            $tenant = \App\Models\Tenant::find($user->tenant_id);
            
            if (!$tenant || !$tenant->is_active) {
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your restaurant tenant account is inactive. Please contact support.'
                ]);
            }
            
            app()->instance('tenant', $tenant);
            \Illuminate\Support\Facades\Session::put('tenant_id', $tenant->id);
        }

        return $next($request);
    }
}
