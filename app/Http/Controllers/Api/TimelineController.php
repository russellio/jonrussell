<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    /**
     * Get all positions with their companies and skills for the timeline.
     */
    public function index(): JsonResponse
    {
        $positions = Position::with(['company', 'skills'])
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($position) {
                return [
                    'id' => $position->id,
                    'title' => $position->title,
                    'description' => $position->description,
                    'startDate' => $position->start_date->format('Y-m-d'),
                    'endDate' => $position->end_date?->format('Y-m-d'),
                    'months' => $position->months,
                    'isCurrent' => $position->end_date === null,
                    'company' => $position->company ? [
                        'id' => $position->company->id,
                        'name' => $position->company->name,
                        'logo' => [
                            'src' => $position->company->logo_src,
                            'alt' => $position->company->logo_alt,
                            'displayName' => $position->company->logo_display_name,
                        ],
                        'link' => $position->company->link,
                    ] : null,
                    'skills' => $position->skills->map(function ($skill) {
                        return [
                            'id' => $skill->id,
                            'name' => $skill->name,
                        ];
                    })->toArray(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $positions,
        ]);
    }
}
