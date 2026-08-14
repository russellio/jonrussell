<?php

use App\Models\Company;
use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectKeyTakeaway;
use App\Models\ProjectLink;
use App\Models\ProjectTechnology;
use App\Models\ProjectTool;

test('project show returns a single project by slug', function () {
    $company = Company::factory()->create(['name' => 'Acme Corp']);
    $project = Project::factory()->for($company)->create([
        'slug' => 'my-project',
        'title' => 'My Project',
        'byline' => 'A great project',
        'description' => '<p>Details here</p>',
        'order' => 0,
    ]);

    ProjectKeyTakeaway::factory()->for($project)->create(['text' => 'Built with Laravel', 'order' => 0]);

    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'laravel']);
    ProjectTechnology::factory()->for($project)->create(['name' => 'Laravel', 'icon_id' => $icon->id, 'order' => 0]);
    ProjectTool::factory()->for($project)->create(['name' => 'Git', 'order' => 0]);
    ProjectLink::factory()->for($project)->create(['title' => 'GitHub', 'url' => 'https://github.com', 'order' => 0]);

    $response = $this->getJson('/api/projects/my-project');

    $response->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonPath('data.id', 'my-project')
        ->assertJsonPath('data.title', 'My Project')
        ->assertJsonPath('data.byline', 'A great project')
        ->assertJsonPath('data.description', '<p>Details here</p>')
        ->assertJsonPath('data.keyTakeaways.0', 'Built with Laravel')
        ->assertJsonPath('data.technologies.0.name', 'Laravel')
        ->assertJsonPath('data.technologies.0.iconType', 'simple-icons')
        ->assertJsonPath('data.tools.0.name', 'Git')
        ->assertJsonPath('data.links.0.title', 'GitHub')
        ->assertJsonPath('data.company.name', 'Acme Corp');
});

test('project show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/projects/does-not-exist');

    $response->assertNotFound()
        ->assertJson(['success' => false, 'message' => 'Project not found']);
});

test('a 404 response is not marked publicly cacheable', function () {
    $response = $this->getJson('/api/projects/does-not-exist');

    $response->assertNotFound();
    expect($response->headers->get('Cache-Control'))->not->toContain('public');
});

test('project show returns null company when project has no company', function () {
    Project::factory()->create(['slug' => 'solo-project', 'title' => 'Solo Project', 'order' => 0]);

    $response = $this->getJson('/api/projects/solo-project');

    $response->assertOk()
        ->assertJsonPath('data.company', null);
});
