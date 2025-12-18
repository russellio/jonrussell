<?php

use App\Models\Project;
use App\Models\ProjectTechnology;

use function Pest\Laravel\getJson;

uses()->group('api');

it('returns highlighted skills for projects', function () {
    // Create a project
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'byline' => 'Test project byline',
        'order' => 0,
    ]);

    // Create some technologies, some highlighted
    ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'Laravel',
        'order' => 0,
        'is_highlighted' => true,
    ]);

    ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'PHP',
        'order' => 1,
        'is_highlighted' => true,
    ]);

    ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'MySQL',
        'order' => 2,
        'is_highlighted' => false,
    ]);

    // Make API request
    $response = getJson('/api/projects');

    // Assert response structure
    $response->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'highlightedSkills',
                    'technologies',
                ],
            ],
        ]);

    // Get the project data from response
    $projectData = collect($response->json('data'))->firstWhere('id', 'test-project');

    // Assert highlighted skills are returned correctly
    expect($projectData['highlightedSkills'])
        ->toBeArray()
        ->toHaveCount(2)
        ->toContain('Laravel')
        ->toContain('PHP')
        ->not->toContain('MySQL');

    // Assert all technologies are returned
    expect($projectData['technologies'])
        ->toBeArray()
        ->toHaveCount(3);
});

it('returns empty array when project has no highlighted skills', function () {
    // Create a project with no highlighted skills
    $project = Project::create([
        'slug' => 'no-highlights',
        'title' => 'No Highlights Project',
        'byline' => 'Project without highlights',
        'order' => 1,
    ]);

    ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'Vue.js',
        'order' => 0,
        'is_highlighted' => false,
    ]);

    // Make API request
    $response = getJson('/api/projects');

    // Get the project data from response
    $projectData = collect($response->json('data'))->firstWhere('id', 'no-highlights');

    // Assert empty highlighted skills array
    expect($projectData['highlightedSkills'])
        ->toBeArray()
        ->toBeEmpty();
});
