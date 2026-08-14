<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Position;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PositionSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Maps skills to positions based on CSV data where TRUE indicates skill was used in that position.
     */
    public function run(): void
    {
        // Map of company names to their position titles for lookup.
        // Only the four engineering roles carry tech pills; the management /
        // early-career roles are intentionally left without a curated stack.
        $companyPositionMap = [
            'ARCA' => 'Web Software Developer / Build Engineer',
            'Glen Raven, Inc.' => 'Senior Web Application Developer / Certified ScrumMaster',
            'Pioneering Evolution' => 'Software Engineer',
            'Russell I/O, LLC' => 'Senior Full Stack Engineer / Software Developer',
        ];

        // Focused, resume-accurate tech pills per role.
        // Format: 'skill_name' => ['company1', 'company2', ...]
        $skillPositionMap = [
            'PHP 8' => ['ARCA', 'Glen Raven, Inc.', 'Pioneering Evolution'],
            'Laravel' => ['ARCA', 'Glen Raven, Inc.', 'Pioneering Evolution'],
            'MySQL 8' => ['ARCA', 'Glen Raven, Inc.', 'Pioneering Evolution'],
            'JavaScript' => ['ARCA', 'Glen Raven, Inc.', 'Pioneering Evolution', 'Russell I/O, LLC'],
            'jQuery' => ['ARCA'],
            'Jenkins' => ['ARCA'],
            'CI/CD automation' => ['ARCA'],
            'Bootstrap' => ['ARCA', 'Glen Raven, Inc.'],
            'REST APIs' => ['Glen Raven, Inc.', 'Pioneering Evolution'],
            'Azure Pipelines' => ['Glen Raven, Inc.'],
            'SCSS' => ['Glen Raven, Inc.'],
            'Vue 3' => ['Pioneering Evolution'],
            'TypeScript' => ['Pioneering Evolution', 'Russell I/O, LLC'],
            'Pinia' => ['Pioneering Evolution'],
            'Tailwind CSS' => ['Pioneering Evolution'],
            'Node.js' => ['Pioneering Evolution'],
            'React' => ['Russell I/O, LLC'],
            'Storybook' => ['Russell I/O, LLC'],
        ];

        foreach ($skillPositionMap as $skillName => $companyNames) {
            $skill = Skill::where('name', $skillName)->first();

            if (! $skill) {
                continue;
            }

            foreach ($companyNames as $companyName) {
                $company = Company::where('name', $companyName)->first();

                if (! $company) {
                    continue;
                }

                // Find the position for this company
                $positionTitle = $companyPositionMap[$companyName] ?? null;
                if (! $positionTitle) {
                    continue;
                }

                $position = Position::where('company_id', $company->id)
                    ->where('title', $positionTitle)
                    ->first();

                if ($position && ! $position->skills->contains($skill->id)) {
                    $position->skills()->attach($skill->id);
                }
            }
        }
    }
}
