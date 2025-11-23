<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            'AYCON',
            'DI - WSD',
            'DI - PM',
            'DI - Manager',
            'ARCA',
            'Glen Raven',
            'Pioneering Evolution',
            'Current',
        ];

        foreach ($companies as $companyName) {
            Company::updateOrCreate(
                ['name' => $companyName],
                ['name' => $companyName]
            );
        }
    }
}
