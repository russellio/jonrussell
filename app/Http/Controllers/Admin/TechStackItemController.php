<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Icon;
use App\Models\TechStackItem;
use App\Services\TechStackMetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TechStackItemController extends Controller
{
    public function __construct(private TechStackMetricsService $metrics)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $items = TechStackItem::with(['skill', 'icon'])->orderBy('order')->get();

        $data = $items->map(function (TechStackItem $item) {
            return [
                'item' => $item,
                'metrics' => $this->metrics->metricsFor($item),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Supports both new format (icon_id) and legacy format (icon_type + icon_name) for backward compatibility.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_id' => 'nullable|exists:skills,id',
            'name' => 'required|string|max:255',
            'percent' => 'nullable|integer|min:0|max:100',
            'icon_id' => 'nullable|exists:icons,id',
            'icon_type' => 'nullable|string|in:fa,si',
            'icon_name' => 'nullable|string|max:255|required_with:icon_type',
            'active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        // Handle backward compatibility: if icon_type and icon_name provided, find or create icon
        if ($request->has('icon_type') && $request->has('icon_name') && !$request->has('icon_id')) {
            $icon = Icon::firstOrCreate(
                [
                    'icon_type' => $request->icon_type,
                    'icon_name' => $request->icon_name,
                ]
            );
            $validated['icon_id'] = $icon->id;
        }

        // Remove legacy fields from validated data
        unset($validated['icon_type'], $validated['icon_name']);

        $item = TechStackItem::create($validated);

        return response()->json([
            'success' => true,
            'data' => $item->load(['skill', 'icon']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TechStackItem $item): JsonResponse
    {
        $item->load(['skill', 'icon']);

        return response()->json([
            'success' => true,
            'data' => [
                'item' => $item,
                'metrics' => $this->metrics->metricsFor($item),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * Supports both new format (icon_id) and legacy format (icon_type + icon_name) for backward compatibility.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $item = TechStackItem::findOrFail($id);

        $validated = $request->validate([
            'skill_id' => 'nullable|exists:skills,id',
            'name' => 'required|string|max:255',
            'percent' => 'nullable|integer|min:0|max:100',
            'icon_id' => 'nullable|exists:icons,id',
            'icon_type' => 'nullable|string|in:fa,si',
            'icon_name' => 'nullable|string|max:255|required_with:icon_type',
            'active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        // Handle backward compatibility: if icon_type and icon_name provided, find or create icon
        if ($request->has('icon_type') && $request->has('icon_name') && !$request->has('icon_id')) {
            $icon = Icon::firstOrCreate(
                [
                    'icon_type' => $request->icon_type,
                    'icon_name' => $request->icon_name,
                ]
            );
            $validated['icon_id'] = $icon->id;
        }

        // Remove legacy fields from validated data
        unset($validated['icon_type'], $validated['icon_name']);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'data' => $item->fresh()->load(['skill', 'icon']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $item = TechStackItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tech stack item deleted successfully',
        ]);
    }
}
