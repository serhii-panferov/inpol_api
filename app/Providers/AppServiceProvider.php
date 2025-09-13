<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('inpol', function () {
            // Configurable rate from config
            $perMinute = (int) config('inpol.rate_per_minute', 30);
            return Limit::perMinute($perMinute)->by('inpol:global');
        });
    }
}
