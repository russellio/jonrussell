<?php

use App\Models\Post;

test('posts api returns only published posts', function () {
    Post::factory()->create(['slug' => 'published-post', 'title' => 'Published Post', 'order' => 0]);
    Post::factory()->unpublished()->create(['slug' => 'draft-post', 'title' => 'Draft Post', 'order' => 1]);

    $response = $this->getJson('/api/posts');

    $response->assertSuccessful();
    $json = $response->json();
    expect($json['success'])->toBeTrue();
    expect($json['data'])->toBeArray();
    expect(count($json['data']))->toBe(1);
    expect($json['data'][0]['id'])->toBe('published-post');
});

test('posts api returns posts ordered by order field', function () {
    Post::factory()->create(['slug' => 'post-2', 'title' => 'Post 2', 'order' => 1]);
    Post::factory()->create(['slug' => 'post-1', 'title' => 'Post 1', 'order' => 0]);

    $response = $this->getJson('/api/posts');

    $response->assertSuccessful();
    $json = $response->json();
    expect($json['data'][0]['id'])->toBe('post-1');
    expect($json['data'][1]['id'])->toBe('post-2');
});

test('posts api item shape has expected keys', function () {
    Post::factory()->create(['slug' => 'shaped-post', 'title' => 'Shaped Post', 'order' => 0]);

    $response = $this->getJson('/api/posts');

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'success',
        'data' => [
            '*' => [
                'id',
                'title',
                'excerpt',
                'year',
                'publishedAt',
                'image',
                'externalUrl',
                'hasBody',
            ],
        ],
    ]);
});

test('post show returns a single published post by slug', function () {
    Post::factory()->withBody()->create([
        'slug' => 'my-post',
        'title' => 'My Post',
        'excerpt' => 'An excerpt',
        'order' => 0,
    ]);

    $response = $this->getJson('/api/posts/my-post');

    $response->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonPath('data.id', 'my-post')
        ->assertJsonPath('data.title', 'My Post')
        ->assertJsonPath('data.excerpt', 'An excerpt')
        ->assertJsonPath('data.hasBody', true);
});

test('post show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/posts/does-not-exist');

    $response->assertNotFound()
        ->assertJson(['success' => false, 'message' => 'Post not found']);
});

test('post show returns 404 for unpublished post', function () {
    Post::factory()->unpublished()->create(['slug' => 'unpublished-post', 'title' => 'Unpublished Post', 'order' => 0]);

    $response = $this->getJson('/api/posts/unpublished-post');

    $response->assertNotFound()
        ->assertJson(['success' => false, 'message' => 'Post not found']);
});
