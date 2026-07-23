<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember('posts:list', now()->addHour(), function () {
            return PostResource::collection(
                Post::published()->orderBy('order')->orderByDesc('published_at')->get()
            )->resolve();
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $cacheKey = "posts:slug:{$slug}";
        $cached = Cache::get($cacheKey);

        if ($cached === null) {
            $post = Post::published()->where('slug', $slug)->first();

            if ($post) {
                Cache::put($cacheKey, $post, now()->addHour());
                $cached = $post;
            } else {
                Cache::put($cacheKey, false, now()->addMinutes(5));
                $cached = false;
            }
        }

        if ($cached === false) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PostResource($cached),
        ]);
    }
}
