<?php

namespace App\Queries;

use App\Http\Resources\ProjectResource;

/**
 * A single project by slug, or `false` when no such project exists.
 */
final class ProjectQuery extends CachedQuery
{
    public function __construct(private readonly string $slug) {}

    public function cacheKey(): string
    {
        return "content:projects:{$this->slug}";
    }

    /**
     * @return array<string, mixed>|false
     */
    protected function compute(): array|false
    {
        $project = ProjectsQuery::base()->where('slug', $this->slug)->first();

        return $project ? (new ProjectResource($project))->resolve() : false;
    }
}
