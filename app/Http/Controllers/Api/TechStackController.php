<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TechStackItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TechStackController extends Controller
{
    /**
     * Get all tech stack items.
     */
    public function index(): JsonResponse
    {
        $data = Cache::remember('techstack.index', 3600, function () {
            return TechStackItem::with(['skill', 'icon'])
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
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
