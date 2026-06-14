<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\TechStackController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1');

Route::middleware('cache.control')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{slug}', [ProjectController::class, 'show']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/tech-stack', [TechStackController::class, 'index']);
    Route::get('/timeline', [TimelineController::class, 'index']);
});
