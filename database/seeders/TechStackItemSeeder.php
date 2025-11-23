<?php

namespace Database\Seeders;

use App\Models\Icon;
use App\Models\Skill;
use App\Models\TechStackItem;
use Illuminate\Database\Seeder;

class TechStackItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techStackItems = [
            [
                'name' => 'Laravel',
                'percent' => 90,
                'icon_type' => 'fa',
                'icon_name' => 'laravel',
                'active' => false,
                'order' => 0,
                'skill_name' => 'Laravel',
            ],
            [
                'name' => 'PHP',
                'percent' => 95,
                'icon_type' => 'fa',
                'icon_name' => 'php',
                'active' => false,
                'order' => 1,
                'skill_name' => 'PHP 8',
            ],
            [
                'name' => 'Vue',
                'percent' => 75,
                'icon_type' => 'fa',
                'icon_name' => 'vuejs',
                'active' => false,
                'order' => 2,
                'skill_name' => 'Vue 3',
            ],
            [
                'name' => 'React',
                'percent' => 45,
                'icon_type' => 'si',
                'icon_name' => 'ReactIcon',
                'active' => true,
                'order' => 3,
                'skill_name' => null, // React not in skills list
            ],
            [
                'name' => 'JavaScript',
                'percent' => 95,
                'icon_type' => 'fa',
                'icon_name' => 'js',
                'active' => false,
                'order' => 4,
                'skill_name' => 'JavaScript',
            ],
            [
                'name' => 'TypeScript',
                'percent' => 90,
                'icon_type' => 'si',
                'icon_name' => 'TypeScriptIcon',
                'active' => false,
                'order' => 5,
                'skill_name' => 'TypeScript',
            ],
            [
                'name' => 'Python',
                'percent' => 20,
                'icon_type' => 'si',
                'icon_name' => 'PythonIcon',
                'active' => true,
                'order' => 6,
                'skill_name' => null, // Python not in skills list
            ],
            [
                'name' => 'REST APIs',
                'percent' => 90,
                'icon_type' => 'fa',
                'icon_name' => 'code',
                'active' => false,
                'order' => 7,
                'skill_name' => 'REST APIs',
            ],
            [
                'name' => 'MySQL / RDMS',
                'percent' => 90,
                'icon_type' => 'si',
                'icon_name' => 'MySqlIcon',
                'active' => false,
                'order' => 8,
                'skill_name' => 'MySQL 8',
            ],
            [
                'name' => 'HTML5',
                'percent' => 95,
                'icon_type' => 'fa',
                'icon_name' => 'html5',
                'active' => false,
                'order' => 9,
                'skill_name' => 'HTML5',
            ],
            [
                'name' => 'CSS3',
                'percent' => 95,
                'icon_type' => 'fa',
                'icon_name' => 'css3',
                'active' => false,
                'order' => 10,
                'skill_name' => 'CSS3',
            ],
            [
                'name' => 'PEST / PHPUnit',
                'percent' => 80,
                'icon_type' => 'fa',
                'icon_name' => 'vial',
                'active' => false,
                'order' => 11,
                'skill_name' => 'PHPUnit', // Using PHPUnit as closest match
            ],
            [
                'name' => 'Agile',
                'percent' => 90,
                'icon_type' => 'fa',
                'icon_name' => 'project-diagram',
                'active' => false,
                'order' => 12,
                'skill_name' => null, // Agile not in skills list
            ],
            [
                'name' => 'OOP / MVC',
                'percent' => 90,
                'icon_type' => 'fa',
                'icon_name' => 'sitemap',
                'active' => false,
                'order' => 13,
                'skill_name' => 'MVC', // Using MVC as match
            ],
        ];

        foreach ($techStackItems as $itemData) {
            $skillId = null;
            if ($itemData['skill_name']) {
                $skill = Skill::where('name', $itemData['skill_name'])->first();
                $skillId = $skill?->id;
            }

            // Look up or create icon
            $iconId = null;
            if (isset($itemData['icon_type']) && isset($itemData['icon_name'])) {
                $icon = Icon::firstOrCreate(
                    [
                        'icon_type' => $itemData['icon_type'],
                        'icon_name' => $itemData['icon_name'],
                    ]
                );
                $iconId = $icon->id;
            }

            // Remove icon_type, icon_name, and skill_name from itemData
            unset($itemData['skill_name'], $itemData['icon_type'], $itemData['icon_name']);

            TechStackItem::updateOrCreate(
                [
                    'name' => $itemData['name'],
                ],
                array_merge($itemData, [
                    'skill_id' => $skillId,
                    'icon_id' => $iconId,
                ])
            );
        }
    }
}
