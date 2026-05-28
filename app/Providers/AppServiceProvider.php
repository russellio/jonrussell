<?php

namespace App\Providers;

use App\Console\Commands\DbSql;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->commands([
            DbSql::class,
        ]);
    }
}
