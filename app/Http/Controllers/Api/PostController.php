<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\PostQuery;
use App\Queries\PostsQuery;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => (new PostsQuery)->get(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $post = (new PostQuery($slug))->get();

        if ($post === false) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }
}
