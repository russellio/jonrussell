<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Get all projects with their relationships.
     */
    public function index(): JsonResponse
    {
        $projects = Project::with([
            'company',
            'keyTakeaways',
            'images',
            'links',
            'technologies.icon',
            'tools.icon',
            'awards',
        ])
            ->orderBy('order')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->slug,
                    'title' => $project->title,
                    'byline' => $project->byline,
                    'keyTakeaways' => $project->keyTakeaways->pluck('text')->toArray(),
                    'description' => $project->description,
                    'highlightedSkills' => $project->technologies->pluck('name')->toArray(),
                    'technologies' => $project->technologies->map(function ($tech) {
                        return [
                            'name' => $tech->name,
                            'iconType' => $tech->icon?->icon_type,
                            'iconName' => $tech->icon?->icon_name,
                        ];
                    })->toArray(),
                    'tools' => $project->tools->map(function ($tool) {
                        return [
                            'name' => $tool->name,
                            'iconType' => $tool->icon?->icon_type,
                            'iconName' => $tool->icon?->icon_name,
                        ];
                    })->toArray(),
                    'company' => $project->company ? [
                        'name' => $project->company->name,
                        'logo' => [
                            'src' => null,
                            'alt' => null,
                            'displayName' => false,
                        ],
                        'link' => null,
                    ] : null,
                    'primaryImage' => $project->primary_image_src ? [
                        'src' => $project->primary_image_src,
                        'title' => $project->primary_image_title,
                        'alt' => $project->primary_image_alt ?? $project->primary_image_title,
                    ] : null,
                    'bgImage' => $project->bg_image,
                    'images' => $project->images->map(function ($image) {
                        return [
                            'src' => $image->src,
                            'title' => $image->title,
                            'alt' => $image->alt ?? $image->title,
                        ];
                    })->toArray(),
                    'bgPositionX' => $project->bg_position_x,
                    'bgPositionY' => $project->bg_position_y,
                    'links' => $project->links->map(function ($link) {
                        return [
                            'title' => $link->title,
                            'url' => $link->url,
                        ];
                    })->toArray(),
                    'awards' => $project->awards->pluck('text')->toArray(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }
}
