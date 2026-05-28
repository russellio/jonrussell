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
        $project = Cache::remember("projects:slug:{$slug}", now()->addHour(), function () use ($slug) {
            return $this->getProjectsQuery()
                ->where('slug', $slug)
                ->first();
        });

        if (! $project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
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
