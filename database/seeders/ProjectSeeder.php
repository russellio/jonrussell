<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectAward;
use App\Models\ProjectImage;
use App\Models\ProjectKeyTakeaway;
use App\Models\ProjectLink;
use App\Models\ProjectTechnology;
use App\Models\ProjectTool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectsJsonPath = resource_path('js/data/projects.json');

        if (! file_exists($projectsJsonPath)) {
            $this->command->error('projects.json file not found');

            return;
        }

        $projectsData = json_decode(file_get_contents($projectsJsonPath), true);

        if (! is_array($projectsData)) {
            $this->command->error('Invalid JSON format in projects.json');

            return;
        }

        foreach ($projectsData as $index => $projectData) {
            // Find or create company
            $company = null;
            if (isset($projectData['company']['name'])) {
                $companyName = strip_tags($projectData['company']['name']);
                $company = Company::firstOrCreate(
                    ['name' => $companyName],
                    ['name' => $companyName]
                );
            }

            // Create project
            $project = Project::create([
                'slug' => $projectData['id'] ?? Str::slug($projectData['title']),
                'title' => $projectData['title'],
                'byline' => $projectData['byline'] ?? null,
                'description' => $projectData['description'] ?? null,
                'primary_image_src' => $projectData['primaryImage']['src'] ?? null,
                'primary_image_title' => $projectData['primaryImage']['title'] ?? null,
                'primary_image_alt' => $projectData['primaryImage']['alt'] ?? null,
                'bg_image' => $projectData['bgImage'] ?? null,
                'bg_position_x' => $projectData['bgPositionX'] ?? null,
                'bg_position_y' => $projectData['bgPositionY'] ?? null,
                'company_id' => $company?->id,
                'order' => $index,
            ]);

            // Create key takeaways
            if (isset($projectData['keyTakeaways']) && is_array($projectData['keyTakeaways'])) {
                foreach ($projectData['keyTakeaways'] as $order => $takeaway) {
                    ProjectKeyTakeaway::create([
                        'project_id' => $project->id,
                        'text' => $takeaway,
                        'order' => $order,
                    ]);
                }
            }

            // Create images
            if (isset($projectData['images']) && is_array($projectData['images'])) {
                foreach ($projectData['images'] as $order => $image) {
                    ProjectImage::create([
                        'project_id' => $project->id,
                        'src' => $image['src'],
                        'title' => $image['title'] ?? null,
                        'alt' => $image['alt'] ?? null,
                        'order' => $order,
                    ]);
                }
            }

            // Create links
            if (isset($projectData['links']) && is_array($projectData['links'])) {
                foreach ($projectData['links'] as $order => $link) {
                    ProjectLink::create([
                        'project_id' => $project->id,
                        'title' => $link['title'],
                        'url' => $link['url'],
                        'order' => $order,
                    ]);
                }
            }

            // Create technologies
            if (isset($projectData['technologies']) && is_array($projectData['technologies'])) {
                foreach ($projectData['technologies'] as $order => $techName) {
                    $iconId = $this->findIconForName($techName);
                    ProjectTechnology::create([
                        'project_id' => $project->id,
                        'name' => $techName,
                        'icon_id' => $iconId,
                        'order' => $order,
                    ]);
                }
            }

            // Create tools
            if (isset($projectData['tools']) && is_array($projectData['tools'])) {
                foreach ($projectData['tools'] as $order => $toolName) {
                    $iconId = $this->findIconForName($toolName);
                    ProjectTool::create([
                        'project_id' => $project->id,
                        'name' => $toolName,
                        'icon_id' => $iconId,
                        'order' => $order,
                    ]);
                }
            }

            // Create awards
            if (isset($projectData['awards']) && is_array($projectData['awards'])) {
                foreach ($projectData['awards'] as $order => $awardText) {
                    ProjectAward::create([
                        'project_id' => $project->id,
                        'text' => $awardText,
                        'order' => $order,
                    ]);
                }
            }
        }

        $this->command->info('Projects seeded successfully');
    }

    /**
     * Find an icon for a given name by matching common technology/tool names.
     */
    private function findIconForName(string $name): ?int
    {
        $nameLower = strtolower($name);
        $nameNormalized = str_replace([' ', '.', '-', '_'], '', $nameLower);

        // Map common names to icon names
        $iconMap = [
            'laravel' => ['icon_name' => 'laravel', 'icon_type' => 'fa'],
            'php' => ['icon_name' => 'php', 'icon_type' => 'fa'],
            'vue' => ['icon_name' => 'vuejs', 'icon_type' => 'fa'],
            'vuejs' => ['icon_name' => 'vuejs', 'icon_type' => 'fa'],
            'vue.js' => ['icon_name' => 'vuejs', 'icon_type' => 'fa'],
            'react' => ['icon_name' => 'ReactIcon', 'icon_type' => 'si'],
            'javascript' => ['icon_name' => 'js', 'icon_type' => 'fa'],
            'js' => ['icon_name' => 'js', 'icon_type' => 'fa'],
            'typescript' => ['icon_name' => 'TypeScriptIcon', 'icon_type' => 'si'],
            'mysql' => ['icon_name' => 'MySqlIcon', 'icon_type' => 'si'],
            'html' => ['icon_name' => 'html5', 'icon_type' => 'fa'],
            'html5' => ['icon_name' => 'html5', 'icon_type' => 'fa'],
            'css' => ['icon_name' => 'css3', 'icon_type' => 'fa'],
            'css3' => ['icon_name' => 'css3', 'icon_type' => 'fa'],
            'python' => ['icon_name' => 'PythonIcon', 'icon_type' => 'si'],
            'git' => ['icon_name' => null, 'icon_type' => null], // No icon available
            'github' => ['icon_name' => null, 'icon_type' => null],
            'docker' => ['icon_name' => null, 'icon_type' => null],
            'node' => ['icon_name' => null, 'icon_type' => null],
            'nodejs' => ['icon_name' => null, 'icon_type' => null],
        ];

        // Try exact match first
        if (isset($iconMap[$nameNormalized])) {
            $iconInfo = $iconMap[$nameNormalized];
            if ($iconInfo['icon_name']) {
                $icon = Icon::where('icon_type', $iconInfo['icon_type'])
                    ->where('icon_name', $iconInfo['icon_name'])
                    ->first();
                if ($icon) {
                    return $icon->id;
                }
            }
        }

        // Try partial match
        foreach ($iconMap as $key => $iconInfo) {
            if (str_contains($nameNormalized, $key) && $iconInfo['icon_name']) {
                $icon = Icon::where('icon_type', $iconInfo['icon_type'])
                    ->where('icon_name', $iconInfo['icon_name'])
                    ->first();
                if ($icon) {
                    return $icon->id;
                }
            }
        }

        return null;
    }
}
