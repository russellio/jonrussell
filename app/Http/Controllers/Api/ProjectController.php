<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => (new ProjectsQuery)->get(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $project = (new ProjectQuery($slug))->get();

        if ($project === false) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }
}
