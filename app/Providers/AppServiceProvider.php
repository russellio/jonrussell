<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CompanyStatsService;
use App\Services\CompanyStatsServiceImpl;
use App\Services\TechStackMetricsService;
use App\Services\TechStackMetricsServiceImpl;

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

        $this->app->bind(CompanyStatsService::class, CompanyStatsServiceImpl::class);
        $this->app->bind(TechStackMetricsService::class, TechStackMetricsServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
