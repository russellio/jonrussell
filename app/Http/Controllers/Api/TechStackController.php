<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\TechStackQuery;
use Illuminate\Http\JsonResponse;

class TechStackController extends Controller
{
    /**
     * Get all tech stack items.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => (new TechStackQuery)->get(),
        ]);
    }
}
