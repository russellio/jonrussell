<?php

use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\TechStackController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\TechStackItemController;
use App\Http\Controllers\ContactController;
use App\Services\CompanyStatsService;
use App\Services\TechStackMetricsService;
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

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/tech-stack', [TechStackController::class, 'index']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('positions', PositionController::class);
    Route::apiResource('tech-stack-items', TechStackItemController::class);

    // Explicit route model binding example for companies
    Route::get('companies/{company}/stats', function (\App\Models\Company $company, CompanyStatsService $stats) {
        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company->load('positions'),
                'stats' => $stats->statsFor($company),
            ],
        ]);
    });

    // Explicit route model binding example for tech stack items
    Route::get('tech-stack-items/{techStackItem}/metrics', function (\App\Models\TechStackItem $techStackItem, TechStackMetricsService $metrics) {
        return response()->json([
            'success' => true,
            'data' => [
                'item' => $techStackItem->load(['skill', 'icon']),
                'metrics' => $metrics->metricsFor($techStackItem),
            ],
        ]);
    });
});
