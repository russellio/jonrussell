<?php

use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

test('post page renders for a published post', function () {
    Post::factory()->create(['slug' => 'published-post', 'title' => 'Published Post', 'order' => 0]);

    $response = $this->get('/posts/published-post');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Post')
        ->has('post')
    );
});

test('post page renders for an image-only post body', function () {
    Post::factory()->create([
        'slug' => 'image-only-post',
        'title' => 'Image Only Post',
        'body' => '<p><img src="/a.jpg" alt=""><img src="/b.jpg" alt=""></p>',
        'order' => 0,
    ]);

    $response = $this->get('/posts/image-only-post');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Post')
        ->where('post.hasBody', true)
        ->where('post.averageWordLength', null)
    );
});

test('post page returns 404 for an unpublished post', function () {
    Post::factory()->unpublished()->create(['slug' => 'unpublished-post', 'title' => 'Unpublished Post', 'order' => 0]);

    $response = $this->get('/posts/unpublished-post');

    $response->assertNotFound();
});

test('post page returns 404 for a missing slug', function () {
    $response = $this->get('/posts/does-not-exist');

    $response->assertNotFound();
});
