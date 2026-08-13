<?php

namespace App\Queries;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;

/**
 * Every project, in display order, with all six child collections.
 */
final class ProjectsQuery extends CachedQuery
{
    /**
     * Relations a fully-rendered project payload needs. Shared with
     * {@see ProjectQuery} so the list and detail shapes cannot drift.
     *
     * @var array<int, string>
     */
    public const RELATIONS = [
        'company',
        'keyTakeaways',
        'images',
        'links',
        'technologies.icon',
        'tools.icon',
        'awards',
    ];

    /**
     * @return Builder<Project>
     */
    public static function base(): Builder
    {
        return Project::query()->with(self::RELATIONS)->orderBy('order');
    }

    public function cacheKey(): string
    {
        return 'content:projects:list';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function compute(): array
    {
        return ProjectResource::collection(self::base()->get())->resolve();
    }
}
