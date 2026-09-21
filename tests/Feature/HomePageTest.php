<?php

use App\Models\Position;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

dataset('home_routes', [
    '/' => ['/', null],
    '/about' => ['/about', 'about'],
    '/tech-stack' => ['/tech-stack', 'tech-stack'],
    '/skills' => ['/skills', 'skills'],
    '/experience' => ['/experience', 'experience'],
    '/projects' => ['/projects', 'projects'],
    '/posts' => ['/posts', 'posts'],
    '/contact' => ['/contact', 'contact'],
]);

beforeEach(function () {
    TechStackItem::factory()->create();

    $skillType = SkillType::factory()->create();
    Skill::factory()->create(['skill_type_id' => $skillType->id]);

    // Factory default already includes a company and a non-empty description,
    // which is what TimelineQuery's curation filter requires to keep the row.
    Position::factory()->create();

    Project::factory()->create();
    Post::factory()->create(['published_at' => now()->subDay()]);
});

test('home page renders with props and scrollTo', function (string $uri, ?string $scrollTo) {
    $response = $this->get($uri);

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Home')
        ->has('techStack', 1)
        ->has('skillTypes', 1)
        ->has('positions', 1)
        ->has('projects', 1)
        ->has('posts', 1)
        ->has('posts.0.body')
        ->where('scrollTo', $scrollTo)
    );
})->with('home_routes');

test('home page batches its five cache reads into a single query', function () {
    // Sentry flagged an N+1: on the `database` cache driver, each of the five
    // CachedQuery props issued its own `select * from cache where key in (?)`.
    // The `array` driver used elsewhere in this suite doesn't touch SQL at all,
    // so this test opts into `database` specifically to catch a regression.
    config(['cache.default' => 'database']);

    DB::enableQueryLog();

    $this->get('/')->assertOk();

    $cacheTableQueries = collect(DB::getQueryLog())
        ->filter(fn (array $query) => str_contains($query['query'], 'from "cache"') || str_contains($query['query'], 'from `cache`'));

    expect($cacheTableQueries)->toHaveCount(1);
});
