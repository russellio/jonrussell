<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Database\Seeder;

class SkillTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skillTypes = [
            [
                'name' => 'Software Engineering',
                'slug' => 'software-engineering',
                'order' => 1,
                'skills' => [
                    'PHP 8',
                    'JavaScript',
                    'TypeScript',
                    'SQL',
                    'MySQL 8',
                    'Node.js',
                    'Laravel',
                    'Vue 3',
                    'Vuex',
                    'Pinia',
                    'jQuery',
                    'webpack',
                    'Vite',
                    'HTML5',
                    'CSS3',
                    'SCSS',
                    'Bootstrap',
                    'Tailwind CSS',
                    'JSON',
                    'XML',
                    'Git',
                    'Jira',
                    'REST APIs',
                    'Microservices',
                    'OOP',
                    'MVC',
                    'SDLC',
                    'SaaS',
                    'SEO',
                    'a11y',
                ],
            ],
            [
                'name' => 'Architecture & DevOps',
                'slug' => 'architecture-devops',
                'order' => 2,
                'skills' => [
                    'AWS',
                    'Linux',
                    'Docker',
                    'CI/CD automation',
                    'Jenkins',
                    'Azure Pipelines',
                    'Github',
                    'Bitbucket',
                ],
            ],
            [
                'name' => 'Quality & Collaboration',
                'slug' => 'quality-collaboration',
                'order' => 3,
                'skills' => [
                    'Unit testing',
                    'PEST',
                    'PHPUnit',
                    'Code reviews',
                    'Pair programming',
                    'API optimization',
                    'Plays well with others',
                ],
            ],
            [
                'name' => 'Leadership & Team Building',
                'slug' => 'leadership-team-building',
                'order' => 4,
                'skills' => [
                    'Team mentoring',
                    'Innovative process improvement',
                    'Experienced Manager, Project Manager',
                    'Certified ScrumMaster (CSM)',
                ],
            ],
        ];

        foreach ($skillTypes as $skillTypeData) {
            $skills = $skillTypeData['skills'];
            unset($skillTypeData['skills']);

            $skillType = SkillType::updateOrCreate(
                ['slug' => $skillTypeData['slug']],
                $skillTypeData
            );

            // Delete existing skills for this type to avoid duplicates on re-seed
            Skill::where('skill_type_id', $skillType->id)->delete();

            foreach ($skills as $index => $skillName) {
                Skill::create([
                    'skill_type_id' => $skillType->id,
                    'name' => $skillName,
                    'order' => $index,
                ]);
            }
        }
    }
}
