<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillTypeResource;
use App\Models\SkillType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember('skills.index', 3600, function () {
            $skillTypes = SkillType::with(['skills.icon'])
                ->orderBy('order')
                ->orderBy('name')
                ->get();

            return SkillTypeResource::collection($skillTypes)->toArray(request());
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
