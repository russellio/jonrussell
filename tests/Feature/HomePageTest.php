<?php

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

test('home page renders with props and scrollTo', function (string $uri, ?string $scrollTo) {
    $response = $this->get($uri);

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Home')
        ->has('techStack')
        ->has('skillTypes')
        ->has('positions')
        ->has('projects')
        ->has('posts')
        ->where('scrollTo', $scrollTo)
    );
})->with('home_routes');
