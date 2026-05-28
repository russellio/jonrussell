<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillTypeResource;
use App\Models\SkillType;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        $skillTypes = SkillType::with(['skills.icon'])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => SkillTypeResource::collection($skillTypes),
        ]);
    }
}
