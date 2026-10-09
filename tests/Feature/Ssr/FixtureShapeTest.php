<?php

use App\Models\Position;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;

/*
|--------------------------------------------------------------------------
| SSR fixture drift guard
|--------------------------------------------------------------------------
|
| tests/ssr/fixtures/*.json are captured Inertia page objects, and they are the
| shared input for two things: the CI render smoke test (pnpm test:ssr) and the
| production deploy gate (php artisan inertia:verify-render). That makes them
| load-bearing -- if a controller gains or renames a prop and the fixture is not
| recaptured, both guards keep rendering a stale payload and quietly stop
| reflecting the real page.
|
| These tests compare the live controller's prop keys against each fixture, so
| drift fails here rather than silently degrading the guards.
|
| To recapture after an intentional prop change, request the page and extract the
| <script data-page="app" type="application/json"> payload from the HTML. Sending
| `X-Inertia: true` returns 409 on a version mismatch, so parse the HTML instead.
|
*/

/**
 * @return array<string, mixed>
 */
function ssrFixture(string $name): array
{
    $path = base_path("tests/ssr/fixtures/{$name}");

    expect($path)->toBeReadableFile();

    /** @var array<string, mixed> $decoded */
    $decoded = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

    return $decoded;
}

/**
 * @return list<string>
 */
function livePropKeys(string $uri): array
{
    $page = test()->get($uri)->viewData('page');

    $keys = array_keys($page['props']);
    sort($keys);

    return $keys;
}

/**
 * @return list<string>
 */
function fixturePropKeys(string $name): array
{
    $keys = array_keys(ssrFixture($name)['props']);
    sort($keys);

    return $keys;
}

test('the home fixture matches the live Home page contract', function () {
    TechStackItem::factory()->create();

    $skillType = SkillType::factory()->create();
    Skill::factory()->create(['skill_type_id' => $skillType->id]);
    Position::factory()->create();
    Project::factory()->create();
    Post::factory()->create(['published_at' => now()->subDay()]);

    $fixture = ssrFixture('home.json');

    expect($fixture['component'])->toBe('Home');
    expect(fixturePropKeys('home.json'))->toBe(livePropKeys('/'));
});

test('the post fixture matches the live Post page contract', function () {
    $post = Post::factory()->create(['published_at' => now()->subDay()]);

    $fixture = ssrFixture('post.json');

    expect($fixture['component'])->toBe('Post');
    expect(fixturePropKeys('post.json'))->toBe(livePropKeys("/posts/{$post->slug}"));
});
