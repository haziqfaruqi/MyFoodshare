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
     */
    public function boot(): void
    {
        // Force HTTPS when using ngrok or similar tunneling services
        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || 
            request()->server('HTTP_X_FORWARDED_SSL') === 'on' ||
            str_contains(request()->server('HTTP_HOST', ''), '.ngrok.io')) {
            \URL::forceScheme('https');
        }
    }
}