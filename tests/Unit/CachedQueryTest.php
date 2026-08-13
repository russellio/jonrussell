<?php

use App\Models\Post;
use App\Models\Project;
use App\Queries\PostQuery;
use App\Queries\PostsQuery;
use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use App\Queries\SkillsQuery;
use App\Queries\TechStackQuery;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Cache;

test('every query owns a distinct key under the content namespace', function () {
    $keys = [
        (new TechStackQuery)->cacheKey(),
        (new SkillsQuery)->cacheKey(),
        (new TimelineQuery)->cacheKey(),
        (new ProjectsQuery)->cacheKey(),
        (new ProjectQuery('some-slug'))->cacheKey(),
        (new PostsQuery)->cacheKey(),
        (new PostQuery('some-slug'))->cacheKey(),
    ];

    expect($keys)->each->toStartWith('content:');
    expect(array_unique($keys))->toHaveCount(count($keys));
});

test('get caches the computed payload under the declared key', function () {
    Post::factory()->create(['title' => 'Cached Post', 'published_at' => now()->subDay()]);

    $query = new PostsQuery;
    expect(Cache::get($query->cacheKey()))->toBeNull();

    $payload = $query->get();

    expect($payload)->toHaveCount(1);
    expect(Cache::get($query->cacheKey()))->toBe($payload);
});

test('get serves the cached payload without re-reading the database', function () {
    $query = new PostsQuery;
    $query->get();

    // Written behind the query's back — a cache hit must not see it.
    Post::withoutEvents(fn () => Post::factory()->create(['published_at' => now()->subDay()]));

    expect($query->get())->toBe([]);
});

test('forget clears the payload so the next get recomputes', function () {
    $query = new PostsQuery;
    $query->get();

    Post::withoutEvents(fn () => Post::factory()->create(['title' => 'Late Arrival', 'published_at' => now()->subDay()]));
    $query->forget();

    expect($query->get())->toHaveCount(1);
});

test('a singular query caches a miss as false rather than recomputing', function () {
    $query = new PostQuery('no-such-post');

    expect($query->get())->toBeFalse();
    expect(Cache::get($query->cacheKey()))->toBeFalse();
});

test('saving a model busts the list and detail keys it feeds', function () {
    $project = Project::factory()->create(['slug' => 'busted']);

    $list = new ProjectsQuery;
    $detail = new ProjectQuery('busted');
    $list->get();
    $detail->get();

    expect(Cache::get($list->cacheKey()))->not->toBeNull();
    expect(Cache::get($detail->cacheKey()))->not->toBeNull();

    $project->update(['title' => 'Renamed']);

    expect(Cache::get($list->cacheKey()))->toBeNull();
    expect(Cache::get($detail->cacheKey()))->toBeNull();
});

test('renaming a slug busts the old detail key too', function () {
    $project = Project::factory()->create(['slug' => 'old-slug']);

    $old = new ProjectQuery('old-slug');
    $old->get();
    expect(Cache::get($old->cacheKey()))->not->toBeNull();

    $project->update(['slug' => 'new-slug']);

    expect(Cache::get($old->cacheKey()))->toBeNull();
});
