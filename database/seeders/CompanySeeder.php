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
        // Early-career roles retained in the DB but hidden from the visible
        // Experience timeline (they carry no curated description). See
        // ExperienceSection.vue, which renders only positions with a description.
        $companies = [
            ['name' => 'AYCON'],
            ['name' => 'DI - WSD'],
            [
                'name' => 'Digital Insight',
                'logo_src' => 'ncr-digital-insight.png',
                'logo_alt' => 'Digital Insight Logo',
                'logo_display_name' => false,
                'link' => 'https://www.digitalinsight.com/',
            ],
            [
                'name' => 'ARCA',
                'logo_src' => 'arca-sesami-dark.png',
                'logo_alt' => 'ARCA Logo',
                'logo_display_name' => false,
                'link' => 'https://www.arca.com/',
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
                'name' => 'Russell I/O, LLC',
                'logo_display_name' => true,
                'link' => 'https://russellio.com',
            ],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
}
