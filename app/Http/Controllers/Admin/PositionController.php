<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $positions = Position::with(['company', 'skills'])->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $positions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $skillIds = $validated['skill_ids'] ?? [];
        unset($validated['skill_ids']);

        $position = Position::create($validated);
        $position->skills()->sync($skillIds);

        return response()->json([
            'success' => true,
            'data' => $position->load(['company', 'skills']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $position = Position::with(['company', 'skills'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $position,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $skillIds = $validated['skill_ids'] ?? [];
        unset($validated['skill_ids']);

        $position->update($validated);
        $position->skills()->sync($skillIds);

        return response()->json([
            'success' => true,
            'data' => $position->fresh()->load(['company', 'skills']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully',
        ]);
    }
}
