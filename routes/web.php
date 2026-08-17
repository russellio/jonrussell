<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostPageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home')->defaults('section', null);
Route::get('/about', HomeController::class)->name('about')->defaults('section', 'about');
Route::get('/tech-stack', HomeController::class)->name('tech-stack')->defaults('section', 'tech-stack');
Route::get('/skills', HomeController::class)->name('skills')->defaults('section', 'skills');
Route::get('/experience', HomeController::class)->name('experience')->defaults('section', 'experience');
Route::get('/projects', HomeController::class)->name('projects')->defaults('section', 'projects');
Route::get('/posts', HomeController::class)->name('posts')->defaults('section', 'posts');
Route::get('/contact', HomeController::class)->name('contact')->defaults('section', 'contact');

Route::get('/posts/{slug}', PostPageController::class)->name('posts.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
