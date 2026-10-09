<?php

use App\Models\Position;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\Process\Process;

/*
|--------------------------------------------------------------------------
| End-to-end SSR render
|--------------------------------------------------------------------------
|
| Boots the real SSR bundle and drives a real request through the full
| Laravel -> HttpGateway -> Node seam, asserting the HTML that comes back is
| actually server-rendered.
|
| The assertion is on the markup, not on an exception, because
| HttpGateway::dispatch() returns null before handleSsrFailure() ever runs when
| the body is empty -- so INERTIA_SSR_THROW_ON_ERROR does NOT fire for the
| empty-200 failure mode. It is enabled below anyway, for the failure modes it
| does cover.
|
| The two #app forms are structurally distinct, which makes "did SSR happen" a
| clean binary:
|   server-rendered: <div id="app" data-page="{...}">...markup...</div>
|   CSR fallback:    <script data-page="app" ...>{...}</script><div id="app"></div>
|
| Skipped rather than failed when the prerequisites are absent, so a plain
| `php artisan test` stays fast and green without a build.
|
*/

pest()->group('ssr');

/** Started by this file, so only this file should stop it. */
$ssrProcess = null;

beforeEach(function () use (&$ssrProcess) {
    if (! is_file(base_path('bootstrap/ssr/ssr.js'))) {
        test()->markTestSkipped('No SSR bundle. Run `pnpm run build:ssr` first.');
    }

    // The root view resolves assets through the client manifest; without it Vite
    // throws before SSR is ever reached.
    if (! is_file(public_path('build/manifest.json'))) {
        test()->markTestSkipped('No client build manifest. Run `pnpm run build:ssr` first.');
    }

    // A hot file routes SSR to the Vite dev server instead of the built bundle,
    // which would make this test prove nothing about what ships.
    if (Vite::isRunningHot()) {
        test()->markTestSkipped('Vite is running hot; SSR would bypass the built bundle.');
    }

    config([
        'inertia.ssr.enabled' => true,
        // phpunit.xml sets INERTIA_SSR_ENABLED=false globally; HttpGateway reads
        // config per dispatch, so overriding here is enough.
        'inertia.ssr.throw_on_error' => true,
    ]);

    // Reuse an already-running server (a dev's `composer dev:ssr`, or a prior
    // test) rather than fighting it for the port.
    if (ssrIsHealthy()) {
        return;
    }

    $ssrProcess = new Process(['node', base_path('bootstrap/ssr/ssr.js')], base_path());
    $ssrProcess->start();

    $deadline = microtime(true) + 30;
    while (microtime(true) < $deadline) {
        if (ssrIsHealthy()) {
            return;
        }

        if (! $ssrProcess->isRunning()) {
            test()->fail('SSR server exited during startup: '.$ssrProcess->getErrorOutput());
        }

        usleep(250_000);
    }

    test()->fail('SSR server never became healthy within 30s: '.$ssrProcess->getErrorOutput());
});

afterEach(function () use (&$ssrProcess) {
    if ($ssrProcess instanceof Process && $ssrProcess->isRunning()) {
        $ssrProcess->stop(5);
    }

    $ssrProcess = null;
});

function ssrIsHealthy(): bool
{
    $context = stream_context_create(['http' => ['timeout' => 2, 'ignore_errors' => true]]);
    $result = @file_get_contents('http://127.0.0.1:13714/health', false, $context);

    return is_string($result) && str_contains($result, 'OK');
}

function seedHomeContent(): void
{
    TechStackItem::factory()->create();

    $skillType = SkillType::factory()->create();
    Skill::factory()->create(['skill_type_id' => $skillType->id]);
    Position::factory()->create();
    Project::factory()->create();
    Post::factory()->create(['published_at' => now()->subDay()]);
}

test('the home page is served server-rendered, not as a client-side shell', function () {
    seedHomeContent();

    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toBeString();

    // The SSR form: Inertia puts the page object on the #app div and fills it.
    expect($html)->toContain('<div id="app" data-page=');

    // The CSR fallback form. Its presence means dispatch() returned null and
    // Laravel quietly degraded -- exactly the silent failure this guards.
    expect($html)->not->toContain('<div id="app"></div>');
})->group('ssr');

test('every home section is present in the server-rendered markup', function () {
    seedHomeContent();

    $html = (string) $this->get('/')->assertOk()->getContent();

    // A render that throws part-way still returns 200, so assert the whole page
    // made it rather than trusting the status code.
    foreach (['about', 'tech-stack', 'skills', 'experience', 'projects', 'posts'] as $section) {
        expect($html)->toContain('id="'.$section.'"');
    }
})->group('ssr');

test('a post page is served server-rendered', function () {
    $post = Post::factory()->create(['published_at' => now()->subDay()]);

    $html = (string) $this->get("/posts/{$post->slug}")->assertOk()->getContent();

    expect($html)->toContain('<div id="app" data-page=');
    expect($html)->not->toContain('<div id="app"></div>');
})->group('ssr');
