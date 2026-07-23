<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'about',
    ]);
})->name('about');

Route::get('/tech-stack', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'tech-stack',
    ]);
})->name('tech-stack');

Route::get('/skills', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'skills',
    ]);
})->name('skills');

Route::get('/experience', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'experience',
    ]);
})->name('experience');

Route::get('/projects', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'projects',
    ]);
})->name('projects');

Route::get('/writing', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'writing',
    ]);
})->name('writing');

Route::get('/contact', function () {
    return Inertia::render('Home', [
        'scrollTo' => 'contact',
    ]);
})->name('contact');

Route::get('/posts/{slug}', function (string $slug) {
    $post = Post::published()->where('slug', $slug)->firstOrFail();

    return Inertia::render('Post', [
        'post' => [
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'body' => $post->body,
            'publishedAt' => $post->published_at?->format('M j, Y'),
            'image' => $post->image_src ? [
                'src' => $post->image_src,
                'alt' => $post->image_alt ?? $post->title,
            ] : null,
        ],
    ]);
})->name('posts.show');
