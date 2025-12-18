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
            ['name' => 'AYCON'],
            ['name' => 'DI - WSD'],
            ['name' => 'DI - PM'],
            ['name' => 'DI - Manager'],
            [
                'name' => 'ARCA',
                'logo_src' => 'arca-sesami-dark.png',
                'logo_alt' => 'ARCA Logo',
                'logo_display_name' => false,
                'link' => 'https://www.arca.com/',
            ],
            [
                'name' => 'Glen Raven',
                'logo_src' => 'glen-raven.svg',
                'logo_alt' => 'Glen Raven Logo',
                'logo_display_name' => false,
                'link' => 'https://www.glenraven.com/',
            ],
            [
                'name' => 'Glen Raven, Inc.',
                'logo_src' => 'glen-raven.svg',
                'logo_alt' => 'Glen Raven Logo',
                'logo_display_name' => false,
                'link' => 'https://www.glenraven.com/',
            ],
            [
                'name' => 'Pioneering Evolution',
                'logo_src' => 'pioneering-evolution.png',
                'logo_alt' => 'Pioneering Evolution Logo',
                'logo_display_name' => true,
                'link' => 'https://www.pioneeringevolution.com/',
            ],
            [
                'name' => 'Digital Insight (susidiary of Intuit, acquired by NCR)',
                'logo_src' => 'ncr-digital-insight.png',
                'logo_alt' => 'Digital Insight Logo',
                'logo_display_name' => false,
                'link' => 'https://www.digitalinsight.com/',
            ],
            ['name' => 'Current'],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
}
