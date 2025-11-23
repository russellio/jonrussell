<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TechStackItem;
use Illuminate\Http\JsonResponse;

class TechStackController extends Controller
{
    /**
     * Get all tech stack items.
     */
    public function index(): JsonResponse
    {
        $techStackItems = TechStackItem::with(['skill', 'icon'])
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                return [
                    'tech' => $item->name,
                    'percent' => (string) $item->calculated_percent,
                    'iconType' => $item->icon?->icon_type,
                    'iconName' => $item->icon?->icon_name,
                    'active' => $item->active,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $techStackItems,
        ]);
    }
}
