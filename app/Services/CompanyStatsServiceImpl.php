<?php

namespace App\Services;

use App\Models\Company;

class CompanyStatsServiceImpl implements CompanyStatsService
{
    /**
     * Get statistics for a company.
     */
    public function statsFor(Company $company): array
    {
        // TODO: Implement company statistics calculation
        return [];
    }
}
