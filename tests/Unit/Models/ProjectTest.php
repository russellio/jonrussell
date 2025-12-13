<?php

use App\Models\Company;
use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectAward;
use App\Models\ProjectImage;
use App\Models\ProjectKeyTakeaway;
use App\Models\ProjectLink;
use App\Models\ProjectTechnology;
use App\Models\ProjectTool;

test('project belongs to company', function () {
    $company = Company::create(['name' => 'Test Company']);
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'company_id' => $company->id,
        'order' => 0,
    ]);

    expect($project->company)->toBeInstanceOf(Company::class);
    expect($project->company->id)->toBe($company->id);
});

test('project has many key takeaways', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $takeaway1 = ProjectKeyTakeaway::create([
        'project_id' => $project->id,
        'text' => 'Takeaway 1',
        'order' => 0,
    ]);

    $takeaway2 = ProjectKeyTakeaway::create([
        'project_id' => $project->id,
        'text' => 'Takeaway 2',
        'order' => 1,
    ]);

    expect($project->keyTakeaways)->toHaveCount(2);
    expect($project->keyTakeaways->first()->id)->toBe($takeaway1->id);
});

test('project has many technologies', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $icon = Icon::create(['icon_type' => 'fa', 'icon_name' => 'laravel']);

    $tech1 = ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'Laravel',
        'icon_id' => $icon->id,
        'order' => 0,
    ]);

    $tech2 = ProjectTechnology::create([
        'project_id' => $project->id,
        'name' => 'Vue.js',
        'order' => 1,
    ]);

    expect($project->technologies)->toHaveCount(2);
    expect($project->technologies->first()->id)->toBe($tech1->id);
    expect($project->technologies->first()->icon)->toBeInstanceOf(Icon::class);
});

test('project has many tools', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $tool1 = ProjectTool::create([
        'project_id' => $project->id,
        'name' => 'Git',
        'order' => 0,
    ]);

    $tool2 = ProjectTool::create([
        'project_id' => $project->id,
        'name' => 'Docker',
        'order' => 1,
    ]);

    expect($project->tools)->toHaveCount(2);
    expect($project->tools->first()->id)->toBe($tool1->id);
});

test('project has many images', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $image1 = ProjectImage::create([
        'project_id' => $project->id,
        'src' => 'image1.png',
        'title' => 'Image 1',
        'order' => 0,
    ]);

    $image2 = ProjectImage::create([
        'project_id' => $project->id,
        'src' => 'image2.png',
        'title' => 'Image 2',
        'order' => 1,
    ]);

    expect($project->images)->toHaveCount(2);
    expect($project->images->first()->id)->toBe($image1->id);
});

test('project has many links', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $link1 = ProjectLink::create([
        'project_id' => $project->id,
        'title' => 'Link 1',
        'url' => 'https://example.com',
        'order' => 0,
    ]);

    expect($project->links)->toHaveCount(1);
    expect($project->links->first()->id)->toBe($link1->id);
});

test('project has many awards', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    $award1 = ProjectAward::create([
        'project_id' => $project->id,
        'text' => 'Award 1',
        'order' => 0,
    ]);

    expect($project->awards)->toHaveCount(1);
    expect($project->awards->first()->id)->toBe($award1->id);
});

test('project relationships are ordered correctly', function () {
    $project = Project::create([
        'slug' => 'test-project',
        'title' => 'Test Project',
        'order' => 0,
    ]);

    ProjectKeyTakeaway::create([
        'project_id' => $project->id,
        'text' => 'Second',
        'order' => 1,
    ]);

    ProjectKeyTakeaway::create([
        'project_id' => $project->id,
        'text' => 'First',
        'order' => 0,
    ]);

    expect($project->keyTakeaways->first()->text)->toBe('First');
    expect($project->keyTakeaways->last()->text)->toBe('Second');
});
