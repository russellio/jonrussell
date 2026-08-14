<?php

use App\Models\Position;
use App\Models\Post;
use App\Models\Project;
use App\Queries\PostsQuery;
use App\Queries\ProjectsQuery;
use App\Queries\TimelineQuery;

/**
 * Proves the end-to-end contract through the real Query/Resource code path each
 * query uses to build its cached payload — not just the trait in isolation.
 */
const MALICIOUS_HTML = '<script>alert(1)</script><p>ok</p>';

test('timeline query sanitizes a position description', function () {
    Position::factory()->create(['description' => MALICIOUS_HTML]);

    $payload = (new TimelineQuery)->get();

    expect($payload)->toHaveCount(1);
    expect($payload[0]['description'])->not->toContain('<script>')
        ->and($payload[0]['description'])->not->toContain('alert(1)')
        ->and($payload[0]['description'])->toContain('<p>ok</p>');
});

test('projects query sanitizes a project description', function () {
    Project::factory()->create(['slug' => 'sanitized-project', 'description' => MALICIOUS_HTML]);

    $payload = (new ProjectsQuery)->get();

    expect($payload)->toHaveCount(1);
    expect($payload[0]['description'])->not->toContain('<script>')
        ->and($payload[0]['description'])->not->toContain('alert(1)')
        ->and($payload[0]['description'])->toContain('<p>ok</p>');
});

test('posts query sanitizes a post body', function () {
    Post::factory()->create(['body' => MALICIOUS_HTML, 'published_at' => now()->subDay()]);

    $payload = (new PostsQuery)->get();

    expect($payload)->toHaveCount(1);
    expect($payload[0]['body'])->not->toContain('<script>')
        ->and($payload[0]['body'])->not->toContain('alert(1)')
        ->and($payload[0]['body'])->toContain('<p>ok</p>');
});

test('posts query preserves target and rel attributes on links', function () {
    $body = '<p><a href="https://example.com" target="_blank" rel="noreferrer noopener">link</a></p>';
    Post::factory()->create(['body' => $body, 'published_at' => now()->subDay()]);

    $payload = (new PostsQuery)->get();

    expect($payload)->toHaveCount(1);
    expect($payload[0]['body'])->toContain('target="_blank"')
        ->and($payload[0]['body'])->toContain('rel="noreferrer noopener"');
});
