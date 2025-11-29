<?php

namespace App\Services;

use App\Models\Company;

class CompanyStatsServiceImpl implements CompanyStatsService
{
    public function statsFor(Company $company): array
    {
        return [
            'positions_count' => $company->positions()->count(),
        ];
    }
}