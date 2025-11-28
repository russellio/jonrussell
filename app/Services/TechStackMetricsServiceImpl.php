<?php

namespace App\Services;

use App\Models\TechStackItem;

class TechStackMetricsServiceImpl implements TechStackMetricsService
{
    public function metricsFor(TechStackItem $item): array
    {
        return [
            'calculated_percent' => $item->calculated_percent,
            'has_icon' => $item->icon !== null,
            'is_active' => (bool) $item->active,
        ];
    }
}