<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS URLs when behind a proxy (Railway, etc.)
        // This ensures asset() generates HTTPS URLs
        if (request()->isSecure() || config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
