<?php

namespace App\Queries;

use App\Http\Resources\PostResource;
use App\Models\Post;

/**
 * Published posts, in display order.
 */
final class PostsQuery extends CachedQuery
{
    public function cacheKey(): string
    {
        return 'content:posts:list';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function compute(): array
    {
        $posts = Post::published()
            ->orderBy('order')
            ->orderByDesc('published_at')
            ->get();

        return PostResource::collection($posts)->resolve();
    }
}
