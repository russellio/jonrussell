<?php

namespace App\Services;

use App\Models\Company;

interface CompanyStatsService
{
    /**
     * Return simple statistics for a company.
     */
    public function statsFor(Company $company): array;
}