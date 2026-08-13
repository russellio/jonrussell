<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\TimelineQuery;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => (new TimelineQuery)->get(),
        ]);
    }
}
