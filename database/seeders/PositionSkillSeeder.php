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
        // Map of company names to their position titles for lookup
        $companyPositionMap = [
            'AYCON' => 'Software Engineer',
            'DI - WSD' => 'Web Software Developer',
            'DI - PM' => 'Project Manager',
            'DI - Manager' => 'Manager',
            'ARCA' => 'Senior Software Engineer',
            'Glen Raven' => 'Senior Software Engineer',
            'Pioneering Evolution' => 'Senior Software Engineer',
            'Current' => 'Senior Software Engineer',
        ];

        // Map skills to positions based on CSV TRUE/FALSE values
        // Format: 'skill_name' => ['company1', 'company2', ...]
        $skillPositionMap = [
            // Software Engineering skills
            'PHP 8' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'JavaScript' => ['AYCON', 'DI - WSD', 'ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'TypeScript' => ['Pioneering Evolution'],
            'MySQL 8' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Node.js' => ['Pioneering Evolution'],
            'Laravel' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Vue 3' => ['Pioneering Evolution'],
            'jQuery' => ['AYCON', 'DI - WSD', 'ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'HTML5' => ['AYCON', 'DI - WSD', 'ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'CSS3' => ['AYCON', 'DI - WSD', 'ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'SCSS' => ['Glen Raven'],
            'Bootstrap' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Tailwind CSS' => ['Pioneering Evolution', 'Current'],
            'REST APIs' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Microservices' => ['Pioneering Evolution'],
            'OOP' => ['AYCON', 'DI - WSD', 'ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'MVC' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'SDLC' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'SaaS' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Docker' => ['ARCA', 'Pioneering Evolution'],
            'Linux' => ['Glen Raven', 'Pioneering Evolution'],
            
            // Architecture & DevOps
            'Git' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Bitbucket' => ['ARCA', 'Pioneering Evolution'],
            'AWS' => ['ARCA', 'Pioneering Evolution'],
            'Azure Pipelines' => ['Glen Raven'],
            'Jenkins' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'CI/CD automation' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            
            // Quality & Collaboration
            'Unit testing' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Code reviews' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'Pair programming' => ['ARCA', 'Glen Raven', 'Pioneering Evolution'],
            'API optimization' => ['Pioneering Evolution'],
            
            // Leadership & Team Building
            'Team mentoring' => ['DI - Manager'],
            'Manager' => ['DI - Manager'],
            'Project Manager' => ['DI - PM'],
        ];

        foreach ($skillPositionMap as $skillName => $companyNames) {
            $skill = Skill::where('name', $skillName)->first();
            
            if (!$skill) {
                continue;
            }

            foreach ($companyNames as $companyName) {
                $company = Company::where('name', $companyName)->first();
                
                if (!$company) {
                    continue;
                }

                // Find the position for this company
                $positionTitle = $companyPositionMap[$companyName] ?? null;
                if (!$positionTitle) {
                    continue;
                }

                $position = Position::where('company_id', $company->id)
                    ->where('title', $positionTitle)
                    ->first();

                if ($position && !$position->skills->contains($skill->id)) {
                    $position->skills()->attach($skill->id);
                }
            }
        }
    }
}
