<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkillType;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    /**
     * Get all skills grouped by skill type.
     */
    public function index(): JsonResponse
    {
        $skillTypes = SkillType::with(['skills.icon'])
            ->orderBy('order')
            ->orderBy('name')
            ->get()
            ->map(function ($skillType) {
                return [
                    'id' => $skillType->id,
                    'name' => $skillType->name,
                    'slug' => $skillType->slug,
                    'skills' => $skillType->skills->map(function ($skill) {
                        return [
                            'id' => $skill->id,
                            'name' => $skill->name,
                            'iconType' => $skill->icon?->icon_type,
                            'iconName' => $skill->icon?->icon_name,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $skillTypes,
        ]);
    }
}
