<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/homie', 'Home')->name('homie');

Route::get('/', function () {
    return Inertia::render('SPA');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('SPA', [
        'scrollTo' => 'about',
    ]);
})->name('about');

Route::get('/projects', function () {
    return Inertia::render('SPA', [
        'scrollTo' => 'projects',
    ]);
})->name('projects');

Route::get('/contact', function () {
    return Inertia::render('SPA', [
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
