<?php

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Resources\ProjectResource\RelationManagers\AwardsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\KeyTakeawaysRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\LinksRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\TechnologiesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\ToolsRelationManager;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectAward;
use App\Models\ProjectImage;
use App\Models\ProjectKeyTakeaway;
use App\Models\ProjectLink;
use App\Models\ProjectTechnology;
use App\Models\ProjectTool;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $admin = User::factory()->create(['email' => 'admin@example.com']);
    config(['app.admin_emails' => ['admin@example.com']]);
    $this->actingAs($admin);
});

test('project list page renders and shows records', function () {
    $projects = Project::factory()->count(3)->create();

    Livewire::test(ListProjects::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($projects);
});

test('project create page renders', function () {
    $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
});

test('a project can be created from the admin', function () {
    $company = Company::factory()->create();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'New Project',
            'slug' => 'new-project',
            'byline' => 'A byline',
            'description' => '<p>Body</p>',
            'company_id' => $company->id,
            'order' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Project::class, [
        'title' => 'New Project',
        'slug' => 'new-project',
        'company_id' => $company->id,
    ]);
});

test('a project can be edited from the admin', function () {
    $project = Project::factory()->create(['title' => 'Old Title']);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->assertFormSet(['title' => 'Old Title'])
        ->fillForm(['title' => 'New Title'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($project->refresh()->title)->toBe('New Title');
});

test('each project relation manager renders and lists its records', function (string $manager, string $relation, string $factory) {
    $project = Project::factory()->create();
    $factory::factory()->for($project, 'project')->create();

    Livewire::test($manager, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($project->{$relation});
})->with([
    'key takeaways' => [KeyTakeawaysRelationManager::class, 'keyTakeaways', ProjectKeyTakeaway::class],
    'images' => [ImagesRelationManager::class, 'images', ProjectImage::class],
    'links' => [LinksRelationManager::class, 'links', ProjectLink::class],
    'technologies' => [TechnologiesRelationManager::class, 'technologies', ProjectTechnology::class],
    'tools' => [ToolsRelationManager::class, 'tools', ProjectTool::class],
    'awards' => [AwardsRelationManager::class, 'awards', ProjectAward::class],
]);
