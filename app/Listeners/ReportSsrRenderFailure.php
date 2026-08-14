<?php

namespace App\Listeners;

use Inertia\Ssr\SsrException;
use Inertia\Ssr\SsrRenderFailed;

class ReportSsrRenderFailure
{
    /**
     * Report SSR render failures through the standard exception pipeline
     * (already wired to Sentry via Integration::handles() in bootstrap/app.php).
     */
    public function handle(SsrRenderFailed $event): void
    {
        report(SsrException::fromEvent($event));
    }
}
