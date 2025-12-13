<?php

namespace App\Services;

use App\Models\Company;

interface CompanyStatsService
{
    /**
     * Get statistics for a company.
     *
     * @param Company $company
     * @return array
     */
    public function statsFor(Company $company): array;
}



