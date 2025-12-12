<?php

namespace App\Providers;

use App\Console\Commands\DbSql;
use App\Services\CompanyStatsService;
use App\Services\CompanyStatsServiceImpl;
use App\Services\TechStackMetricsService;
use App\Services\TechStackMetricsServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyStatsService::class, CompanyStatsServiceImpl::class);
        $this->app->bind(TechStackMetricsService::class, TechStackMetricsServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->commands([
            DbSql::class,
        ]);
    }
}
