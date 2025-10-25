<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('SPALayout');
//     return Inertia::render('Projects');
})->name('home');

Route::get('/about', function () {
//    return Inertia::render('SPALayout');
     return Inertia::render('About');
})->name('about');

Route::get('/projects', function () {
//    return Inertia::render('SPALayout');
     return Inertia::render('Projects');
})->name('projects');
