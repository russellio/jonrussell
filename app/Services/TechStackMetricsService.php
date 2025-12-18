<?php

namespace App\Services;

use App\Models\TechStackItem;

interface TechStackMetricsService
{
    /**
     * Get metrics for a tech stack item.
     */
    public function metricsFor(TechStackItem $item): array;
}
