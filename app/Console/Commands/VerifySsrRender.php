<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Vite;
use Inertia\Ssr\Gateway;
use JsonException;

/**
 * Deploy gate: proves the SSR bundle can actually render a page on this host.
 *
 * `php artisan inertia:check-ssr` only hits Inertia's /health endpoint, which is
 * a static object literal that never calls render -- it proves Node bound the
 * port and nothing more. Worse, Inertia's SSR server writes its 200 status line
 * before awaiting the render and swallows any throw to stderr, so a failed
 * render comes back as HTTP 200 with a zero-byte body. A health check cannot see
 * that; only an actual render can.
 *
 * This is also the only guard that runs on the real host, so it is the only one
 * that can catch a bundle that builds and renders fine in CI but fails here.
 */
class VerifySsrRender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inertia:verify-render
        {--fixture=home.json : Fixture under tests/ssr/fixtures to render}
        {--budget= : Max render seconds before failing (defaults to inertia.ssr.timeout)}
        {--min-bytes=5000 : Minimum acceptable rendered body size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Render a real page through the SSR server and fail if it does not come back';

    /**
     * Execute the console command.
     */
    public function handle(Gateway $gateway): int
    {
        $fixture = (string) $this->option('fixture');
        $minBytes = (int) $this->option('min-bytes');
        $budget = $this->resolveBudget();

        // With a hot file present, HttpGateway dispatches to the Vite dev server's
        // /__inertia_ssr instead of the production bundle on :13714 -- so a pass
        // here would prove nothing about what actually ships. public/hot is
        // gitignored and gets left behind when a dev server dies uncleanly, so
        // this is a real trap rather than a theoretical one.
        if (Vite::isRunningHot()) {
            $this->error(
                'A Vite hot file is present, so SSR would be routed to the dev server rather than '
                .'bootstrap/ssr. Remove public/hot (or stop the dev server) before verifying.'
            );

            return self::FAILURE;
        }

        if (! config('inertia.ssr.enabled')) {
            $this->error('inertia.ssr.enabled is false, so there is nothing to verify. Set INERTIA_SSR_ENABLED=true.');

            return self::FAILURE;
        }

        $path = base_path("tests/ssr/fixtures/{$fixture}");

        if (! is_readable($path)) {
            $this->error("Fixture not readable: {$path}");

            return self::FAILURE;
        }

        try {
            $page = $this->decodePage($path);
        } catch (JsonException $e) {
            $this->error("Fixture is not valid JSON: {$e->getMessage()}");

            return self::FAILURE;
        }

        $startedAt = microtime(true);
        $response = $gateway->dispatch($page);
        $elapsed = microtime(true) - $startedAt;

        // dispatch() returns null for every failure mode: a connection error, a
        // non-2xx, and -- critically -- an empty body, which is how a thrown
        // render surfaces. Inertia treats that as a soft failure and silently
        // falls back to client-side rendering, so null here means production
        // would serve a CSR-only page.
        if ($response === null) {
            $this->error(sprintf(
                'SSR render returned nothing after %.2Fs. The server is listening but cannot render '
                .'[%s] -- check `journalctl -u inertia-ssr` for a swallowed render error.',
                $elapsed,
                $this->componentName($page),
            ));

            return self::FAILURE;
        }

        $bytes = strlen($response->body);

        if ($bytes < $minBytes) {
            $this->error(sprintf(
                'SSR body is %d bytes, below the %d-byte floor -- the render looks truncated.',
                $bytes,
                $minBytes,
            ));

            return self::FAILURE;
        }

        if (! str_contains($response->body, '<div id="app" data-page=')) {
            $this->error('SSR body lacks the server-rendered \'<div id="app" data-page=\' marker.');

            return self::FAILURE;
        }

        if ($elapsed > $budget) {
            $this->error(sprintf(
                'SSR rendered %d bytes but took %.2Fs, over the %.2Fs budget. Past '
                .'inertia.ssr.timeout the response silently degrades to client-side rendering.',
                $bytes,
                $elapsed,
                $budget,
            ));

            return self::FAILURE;
        }

        $this->info(sprintf(
            'SSR rendered [%s]: %d bytes in %.2Fs (budget %.2Fs).',
            $this->componentName($page),
            $bytes,
            $elapsed,
            $budget,
        ));

        return self::SUCCESS;
    }

    /**
     * Resolve the render budget in seconds.
     *
     * Defaults to the configured SSR timeout: a render that cannot finish inside
     * it is one Laravel would abandon in favour of client-side rendering, so
     * that value is the threshold that actually matters in production.
     */
    private function resolveBudget(): float
    {
        $option = $this->option('budget');

        if ($option !== null && $option !== '') {
            return (float) $option;
        }

        return (float) config('inertia.ssr.timeout', 3);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodePage(string $path): array
    {
        /** @var array<string, mixed> $decoded */
        $decoded = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /**
     * @param  array<string, mixed>  $page
     */
    private function componentName(array $page): string
    {
        return is_string($page['component'] ?? null) ? $page['component'] : 'unknown';
    }
}
