<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('admin', function (User $user) {
            return $user->hasRole('Admin');
        });

        Gate::define('hr', function (User $user) {
            return $user->hasAnyRole(['Admin', 'HR']);
        });

        Gate::define('employee', function (User $user) {
            return $user->hasAnyRole(['Admin', 'HR', 'Employee']);
        });
    }
}
