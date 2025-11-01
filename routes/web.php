<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('SPA');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('SPA', [
        'scrollTo' => 'about'
    ]);
})->name('about');

Route::get('/projects', function () {
    return Inertia::render('SPA', [
        'scrollTo' => 'projects'
    ]);
})->name('projects');

Route::get('/contact', function () {
    return Inertia::render('SPA', [
        'scrollTo' => 'contact'
    ]);
})->name('contact');
