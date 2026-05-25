<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    public function index(): JsonResponse
    {
        $positions = Position::with(['company', 'skills'])
            ->orderByDesc('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => PositionResource::collection($positions),
        ]);
    }
}
