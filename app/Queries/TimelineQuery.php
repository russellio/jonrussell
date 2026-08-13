<?php

namespace App\Queries;

use App\Http\Resources\PositionResource;
use App\Models\Position;

/**
 * Career timeline, newest role first.
 *
 * Only roles that carry a written description appear. That curation used to live
 * in `ExperienceSection.vue` as `positions.filter(p => Boolean(p.description))`;
 * the SQL below reproduces those semantics exactly — `null` and `''` are dropped,
 * anything else (including `'<p></p>'`) is kept.
 */
final class TimelineQuery extends CachedQuery
{
    public function cacheKey(): string
    {
        return 'content:timeline:list';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function compute(): array
    {
        $positions = Position::query()
            ->with(['company', 'skills'])
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->orderByDesc('start_date')
            ->get();

        return PositionResource::collection($positions)->resolve();
    }
}
