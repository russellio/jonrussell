<?php

namespace App\Queries;

use App\Http\Resources\PostResource;
use App\Models\Post;

/**
 * A single published post by slug, or `false` when no such post exists.
 */
final class PostQuery extends CachedQuery
{
    public function __construct(private readonly string $slug) {}

    public function cacheKey(): string
    {
        return "content:posts:{$this->slug}";
    }

    /**
     * @return array<string, mixed>|false
     */
    protected function compute(): array|false
    {
        $post = Post::published()->where('slug', $this->slug)->first();

        return $post ? (new PostResource($post))->resolve() : false;
    }
}
