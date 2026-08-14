<?php

use App\Models\Company;
use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectKeyTakeaway;
use App\Models\ProjectTechnology;
use App\Models\ProjectTool;

test('projects api returns all projects with relationships', function () {
    $company = Company::factory()->create(['name' => 'Test Company']);
    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'laravel']);

    $project = Project::factory()
        ->for($company)
        ->has(ProjectKeyTakeaway::factory()->state(['text' => 'Key takeaway 1', 'order' => 0]), 'keyTakeaways')
        ->has(ProjectTechnology::factory()->state(['name' => 'Laravel', 'icon_id' => $icon->id, 'order' => 0]), 'technologies')
        ->has(ProjectTool::factory()->state(['name' => 'Git', 'order' => 0]), 'tools')
        ->create([
            'slug' => 'test-project',
            'title' => 'Test Project',
            'byline' => 'Test byline',
            'description' => '<p>Test description</p>',
            'order' => 0,
        ]);

    $response = $this->getJson('/api/projects');

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'success',
        'data' => [
            '*' => [
                'id',
                'title',
                'byline',
                'keyTakeaways',
                'description',
                'highlightedSkills',
                'technologies',
                'tools',
                'company',
                'primaryImage',
                'bgImage',
                'images',
                'links',
                'awards',
            ],
        ],
    ]);

    $json = $response->json();
    expect($json['success'])->toBeTrue();
    expect($json['data'])->toBeArray();
    expect(count($json['data']))->toBe(1);
    expect($json['data'][0]['id'])->toBe('test-project');
    expect($json['data'][0]['title'])->toBe('Test Project');
    expect($json['data'][0]['keyTakeaways'])->toBeArray();
    expect($json['data'][0]['keyTakeaways'][0])->toBe('Key takeaway 1');
    expect($json['data'][0]['technologies'])->toBeArray();
    expect($json['data'][0]['technologies'][0]['name'])->toBe('Laravel');
    expect($json['data'][0]['technologies'][0]['iconType'])->toBe('simple-icons');
    expect($json['data'][0]['technologies'][0]['iconName'])->toBe('laravel');
    expect($json['data'][0]['tools'])->toBeArray();
    expect($json['data'][0]['tools'][0]['name'])->toBe('Git');
});

test('projects api returns projects ordered by order field', function () {
    Project::factory()->create([
        'slug' => 'project-1',
        'title' => 'Project 1',
        'order' => 1,
    ]);

    Project::factory()->create([
        'slug' => 'project-2',
        'title' => 'Project 2',
        'order' => 0,
    ]);

    $response = $this->getJson('/api/projects');

    $response->assertSuccessful();
    $json = $response->json();
    expect($json['data'][0]['id'])->toBe('project-2');
    expect($json['data'][1]['id'])->toBe('project-1');
});

test('projects api handles projects with no relationships', function () {
    Project::factory()->create([
        'slug' => 'minimal-project',
        'title' => 'Minimal Project',
        'order' => 0,
    ]);

    $response = $this->getJson('/api/projects');

    $response->assertSuccessful();
    $json = $response->json();
    expect($json['data'][0]['id'])->toBe('minimal-project');
    expect($json['data'][0]['keyTakeaways'])->toBeArray();
    expect($json['data'][0]['keyTakeaways'])->toBeEmpty();
    expect($json['data'][0]['technologies'])->toBeArray();
    expect($json['data'][0]['technologies'])->toBeEmpty();
    expect($json['data'][0]['tools'])->toBeArray();
    expect($json['data'][0]['tools'])->toBeEmpty();
});
