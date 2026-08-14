<?php

namespace App\Http\Resources\Concerns;

trait SanitizesHtml
{
    /**
     * Strip unsafe markup from rich-text HTML authored via a Filament RichEditor.
     *
     * Defense-in-depth against a compromised admin session, not a fix for a live
     * public-facing XSS vulnerability — this content has no untrusted user input.
     */
    protected function sanitize(?string $html): ?string
    {
        return $html !== null ? clean($html) : null;
    }
}
