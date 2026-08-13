<?php

namespace App\Queries;

use App\Http\Resources\TechStackItemResource;
use App\Models\TechStackItem;

/**
 * Tech stack proficiency bars, in display order.
 */
final class TechStackQuery extends CachedQuery
{
    public function cacheKey(): string
    {
        return 'content:techstack:list';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function compute(): array
    {
        $items = TechStackItem::query()
            ->with(['skill', 'icon'])
            ->orderBy('order')
            ->get();

        return TechStackItemResource::collection($items)->resolve();
    }
}
