<?php

namespace App\Providers;

use App\Console\Commands\DbSql;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Vite as ViteInstance;
use Illuminate\Foundation\ViteException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->registerViteMacros();
        $this->commands([
            DbSql::class,
        ]);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );

        // Laravel's HTTP client defaults to a 30s timeout, which is what let the
        // Inertia SSR gateway (Http::post() to 127.0.0.1:13714, no timeout of its
        // own) hang every request for 30s when the Node SSR worker wedged
        // (JONRUSSELL-DEV-LARAVEL-1W). 3s is plenty for a localhost render; a
        // wedged worker now fails fast and Inertia falls back to client-side
        // rendering instead of the visitor staring at a blank tab. Any call site
        // that sets its own ->timeout() (e.g. ValidTurnstileToken) still wins,
        // this only changes callers relying on the 30s default.
        Http::globalOptions([
            'timeout' => 3,
            'connect_timeout' => 3,
        ]);
    }

    /**
     * Register `Vite::packageAsset()`, which resolves a file emitted from an npm package to its built URL.
     *
     * `Vite::asset()` can't be used for these: pnpm makes Vite key the manifest by realpath, e.g.
     * `node_modules/.pnpm/@fontsource+noto-sans@5.3.0/node_modules/@fontsource/noto-sans/files/...`,
     * so the key changes on every package bump and `Vite::asset()` would throw on every page.
     * Matching on the `node_modules/<package path>` suffix survives version changes. A miss is
     * reported rather than thrown, so a renamed file costs a preload, not the page.
     */
    protected function registerViteMacros(): void
    {
        Vite::macro('packageAsset', function (string $path): ?string {
            /** @var ViteInstance $this */
            if ($this->isRunningHot()) {
                return null;
            }

            $suffix = 'node_modules/'.ltrim($path, '/');

            foreach ($this->manifest($this->buildDirectory) as $key => $chunk) {
                if (str_ends_with($key, $suffix)) {
                    return $this->assetPath($this->buildDirectory.'/'.$chunk['file']);
                }
            }

            report(new ViteException("Unable to locate package asset in Vite manifest: {$path}."));

            return null;
        });
    }
}
