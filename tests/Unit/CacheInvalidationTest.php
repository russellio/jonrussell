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
use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use App\Queries\SkillsQuery;
use App\Queries\TechStackQuery;
use App\Queries\TimelineQuery;
use Illuminate\Support\Facades\Cache;

$childModels = [
    'key takeaway' => ProjectKeyTakeaway::class,
    'image' => ProjectImage::class,
    'link' => ProjectLink::class,
    'technology' => ProjectTechnology::class,
    'tool' => ProjectTool::class,
    'award' => ProjectAward::class,
];

test('creating a project child row busts the list and its parent detail cache', function (string $model) {
    $project = Project::factory()->create(['slug' => 'busted-on-create']);

    $list = new ProjectsQuery;
    $detail = new ProjectQuery('busted-on-create');
    $list->get();
    $detail->get();

    $model::factory()->for($project, 'project')->create();

    expect(Cache::get($list->cacheKey()))->toBeNull();
    expect(Cache::get($detail->cacheKey()))->toBeNull();
})->with($childModels);

test('deleting a project child row busts the list and its parent detail cache', function (string $model) {
    $project = Project::factory()->create(['slug' => 'busted-on-delete']);
    $child = $model::factory()->for($project, 'project')->create();

    $list = new ProjectsQuery;
    $detail = new ProjectQuery('busted-on-delete');
    $list->get();
    $detail->get();

    $child->delete();

    expect(Cache::get($list->cacheKey()))->toBeNull();
    expect(Cache::get($detail->cacheKey()))->toBeNull();
})->with($childModels);

test('renaming an icon busts skills, tech stack, and every project that uses it', function () {
    $icon = Icon::factory()->lucide()->create();

    $viaTechnology = Project::factory()->create(['slug' => 'icon-via-technology']);
    ProjectTechnology::factory()->for($viaTechnology, 'project')->create(['icon_id' => $icon->id]);

    $viaTool = Project::factory()->create(['slug' => 'icon-via-tool']);
    ProjectTool::factory()->for($viaTool, 'project')->create(['icon_id' => $icon->id]);

    $queries = [
        new SkillsQuery,
        new TechStackQuery,
        new ProjectsQuery,
        new ProjectQuery('icon-via-technology'),
        new ProjectQuery('icon-via-tool'),
    ];
    foreach ($queries as $query) {
        $query->get();
        expect(Cache::get($query->cacheKey()))->not->toBeNull();
    }

    $icon->update(['icon_name' => 'renamed-icon']);

    foreach ($queries as $query) {
        expect(Cache::get($query->cacheKey()))->toBeNull();
    }
});

test('deleting an icon busts every project it appeared in before its icon_id foreign keys are nulled', function () {
    $icon = Icon::factory()->lucide()->create();
    $project = Project::factory()->create(['slug' => 'icon-deleted']);
    ProjectTechnology::factory()->for($project, 'project')->create(['icon_id' => $icon->id]);

    $detail = new ProjectQuery('icon-deleted');
    $detail->get();
    expect(Cache::get($detail->cacheKey()))->not->toBeNull();

    $icon->delete();

    expect(Cache::get($detail->cacheKey()))->toBeNull();
});

test('renaming a company busts timeline, projects list, and each of its projects', function () {
    $company = Company::factory()->create();
    Project::factory()->for($company)->create(['slug' => 'company-renamed']);

    $queries = [
        new TimelineQuery,
        new ProjectsQuery,
        new ProjectQuery('company-renamed'),
    ];
    foreach ($queries as $query) {
        $query->get();
        expect(Cache::get($query->cacheKey()))->not->toBeNull();
    }

    $company->update(['name' => 'Renamed Co']);

    foreach ($queries as $query) {
        expect(Cache::get($query->cacheKey()))->toBeNull();
    }
});

test('deleting a company busts each of its projects before company_id is nulled', function () {
    $company = Company::factory()->create();
    Project::factory()->for($company)->create(['slug' => 'company-deleted']);

    $detail = new ProjectQuery('company-deleted');
    $detail->get();
    expect(Cache::get($detail->cacheKey()))->not->toBeNull();

    $company->delete();

    expect(Cache::get($detail->cacheKey()))->toBeNull();
});
