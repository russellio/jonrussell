<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember('projects:list', now()->addHour(), function () {
            return ProjectResource::collection($this->getProjectsQuery()->get())->resolve();
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $cacheKey = "projects:slug:{$slug}";
        $cached = Cache::get($cacheKey);

        if ($cached === null) {
            $project = $this->getProjectsQuery()->where('slug', $slug)->first();

            if ($project) {
                Cache::put($cacheKey, $project, now()->addHour());
                $cached = $project;
            } else {
                Cache::put($cacheKey, false, now()->addMinutes(5));
                $cached = false;
            }
        }

        if ($cached === false) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($cached),
        ]);
    }

    private function getProjectsQuery()
    {
        return Project::with([
            'company',
            'keyTakeaways',
            'images',
            'links',
            'technologies.icon',
            'tools.icon',
            'awards',
        ])->orderBy('order');
    }
}
