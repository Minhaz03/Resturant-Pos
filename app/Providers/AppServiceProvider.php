<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Implicitly grant 'super_admin' and 'owner' roles all permissions
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('super_admin') || $user->hasRole('owner')) {
                    return true;
                }
            }
        });
    }
}
