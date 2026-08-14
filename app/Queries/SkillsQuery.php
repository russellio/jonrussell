<?php

namespace App\Queries;

use App\Http\Resources\SkillTypeResource;
use App\Models\SkillType;

/**
 * Skill types with their skills, ordered for display.
 */
final class SkillsQuery extends CachedQuery
{
    public function cacheKey(): string
    {
        return 'content:skills:list';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function compute(): array
    {
        $skillTypes = SkillType::query()
            ->with(['skills.icon'])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return SkillTypeResource::collection($skillTypes)->resolve();
    }
}
