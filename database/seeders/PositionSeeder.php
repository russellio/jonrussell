<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'company' => 'AYCON',
                'title' => 'Software Engineer',
                'start_date' => '2004-05-01',
                'end_date' => '2007-01-01',
            ],
            [
                'company' => 'DI - WSD',
                'title' => 'Web Software Developer',
                'start_date' => '2007-02-01',
                'end_date' => '2009-05-01',
            ],
            [
                'company' => 'DI - PM',
                'title' => 'Project Manager',
                'start_date' => '2009-06-01',
                'end_date' => '2012-07-01',
            ],
            [
                'company' => 'DI - Manager',
                'title' => 'Manager',
                'start_date' => '2012-08-01',
                'end_date' => '2015-01-01',
            ],
            [
                'company' => 'ARCA',
                'title' => 'Senior Software Engineer',
                'start_date' => '2015-01-01',
                'end_date' => '2016-10-01',
            ],
            [
                'company' => 'Glen Raven',
                'title' => 'Senior Software Engineer',
                'start_date' => '2016-11-01',
                'end_date' => '2023-05-01',
            ],
            [
                'company' => 'Pioneering Evolution',
                'title' => 'Senior Software Engineer',
                'start_date' => '2024-03-01',
                'end_date' => '2025-09-01',
            ],
            [
                'company' => 'Current',
                'title' => 'Senior Software Engineer',
                'start_date' => '2025-09-01',
                'end_date' => null, // Current position, no end date
            ],
        ];

        foreach ($positions as $positionData) {
            $company = Company::where('name', $positionData['company'])->first();

            if ($company) {
                Position::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'start_date' => $positionData['start_date'],
                    ],
                    [
                        'title' => $positionData['title'],
                        'end_date' => $positionData['end_date'],
                    ]
                );
            }
        }
    }
}
