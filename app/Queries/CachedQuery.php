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
        return $this->resolve(Cache::get($this->cacheKey()));
    }

    /**
     * Resolve several queries' payloads with a single batched cache read
     * instead of one `Cache::get()` round trip per query.
     *
     * @param  array<string, CachedQuery>  $queries
     * @return array<string, mixed>
     */
    public static function resolveMany(array $queries): array
    {
        $keysByResultKey = [];

        foreach ($queries as $resultKey => $query) {
            $keysByResultKey[$resultKey] = $query->cacheKey();
        }

        // Cache::many() treats a string-keyed array as `cacheKey => default`, so
        // it must get a plain list of cache key strings, not $keysByResultKey.
        $cached = Cache::many(array_values($keysByResultKey));

        $results = [];

        foreach ($queries as $resultKey => $query) {
            $results[$resultKey] = $query->resolve($cached[$keysByResultKey[$resultKey]]);
        }

        return $results;
    }

    public function forget(): void
    {
        Cache::forget($this->cacheKey());
    }

    private function resolve(mixed $cached): mixed
    {
        if ($cached !== null) {
            return $cached;
        }

        $value = $this->compute();

        Cache::put($this->cacheKey(), $value, $value === false ? static::MISS_TTL : static::TTL);

        return $value;
    }
}
