<?php

namespace App\Providers;

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
     *
     * Middleware aliases (farmer, buyer, admin) are registered in
     * bootstrap/app.php via ->withMiddleware(), which is the
     * standard approach for Laravel 13.
     */
    public function boot(): void
    {
        //
    }
}
