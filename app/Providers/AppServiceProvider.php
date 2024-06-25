<?php

namespace App\Providers;

// In AppServiceProvider or a dedicated ServiceProvider

use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
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
        Blade::if('role', function ($role) {
            return Auth::check() && User::find(Auth::id())->hasRole($role);
        });
    }
}
