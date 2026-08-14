<?php

namespace App\Queries;

use Illuminate\Support\Facades\Cache;

/**
 * Base for the read-only, cached content payloads shared by the Inertia (web)
 * and JSON (api) paths.
 *
 * The contract every subclass honours:
 *  - read-only — a query never writes
 *  - returns a plain array (or `false` for a singular payload that does not exist)
 *  - owns exactly one cache key, declared in exactly one place
 *
 * Cache keys follow `content:{type}:{scope}`. Nothing outside a query class may
 * name a key: model busters call `(new TimelineQuery)->forget()`, never a literal.
 */
abstract class CachedQuery
{
    /** Seconds a resolved payload stays cached. */
    protected const TTL = 3600;

    /**
     * Seconds a "not found" answer stays cached. Shorter than TTL so a slug that
     * starts existing appears quickly, long enough to blunt a slug-scan flood.
     */
    protected const MISS_TTL = 300;

    abstract public function cacheKey(): string;

    /**
     * Resolve the payload from the database.
     *
     * List queries return an array (empty when there is no content). Singular
     * queries return `false` when the record does not exist — `null` cannot be
     * used because Laravel's cache stores cannot distinguish a stored null from
     * a miss.
     */
    abstract protected function compute(): mixed;

    public function get(): mixed
    {
        $key = $this->cacheKey();
        $cached = Cache::get($key);

        if ($cached !== null) {
            return $cached;
        }

        $value = $this->compute();

        Cache::put($key, $value, $value === false ? static::MISS_TTL : static::TTL);

        return $value;
    }

    public function forget(): void
    {
        Cache::forget($this->cacheKey());
    }
}
