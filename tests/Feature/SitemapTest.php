<?php

use App\Models\Post;

test('sitemap lists the static section routes', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('content-type', 'application/xml');

    foreach (['/', '/about', '/tech-stack', '/skills', '/experience', '/projects', '/posts', '/contact'] as $path) {
        $response->assertSee(url($path), false);
    }
});

test('sitemap includes published posts with a body and their lastmod', function () {
    $post = Post::factory()->withBody()->create(['slug' => 'published-post']);

    $response = $this->get('/sitemap.xml');

    $response->assertSee(route('posts.show', $post->slug), false);
    $response->assertSee($post->updated_at->toAtomString(), false);
});

test('sitemap excludes unpublished posts', function () {
    Post::factory()->unpublished()->create(['slug' => 'unpublished-post']);

    $response = $this->get('/sitemap.xml');

    $response->assertDontSee(route('posts.show', 'unpublished-post'), false);
});

test('sitemap excludes external-link posts with no body', function () {
    Post::factory()->external()->create(['slug' => 'external-post']);

    $response = $this->get('/sitemap.xml');

    $response->assertDontSee(route('posts.show', 'external-post'), false);
});
