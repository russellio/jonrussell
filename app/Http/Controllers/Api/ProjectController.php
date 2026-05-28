<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = $this->getProjectsQuery()->get();

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $project = $this->getProjectsQuery()
            ->where('slug', $slug)
            ->first();

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
