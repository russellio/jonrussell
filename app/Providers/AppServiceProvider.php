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
        \Sentry\init([
            'dsn' => 'https://e4b4ebfec353819d82696d38aa734bd3@o4509299183583232.ingest.us.sentry.io/4509299188170752',
            'release' => 'jonrussell-dev@1.0.1',
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
