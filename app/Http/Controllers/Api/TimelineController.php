<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TimelineController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember('timeline.index', 3600, function () {
            $positions = Position::with(['company', 'skills'])
                ->orderByDesc('start_date')
                ->get();

            return PositionResource::collection($positions)->toArray(request());
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
