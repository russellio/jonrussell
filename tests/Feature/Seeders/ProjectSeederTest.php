<?php

use App\Models\Company;
use App\Models\Project;
use Database\Seeders\CompanySeeder;
use Database\Seeders\ProjectSeeder;

uses()->group('seeders');

test('project seeder updates existing companies with logo information', function () {
    // First, run CompanySeeder which creates companies without logo info
    (new CompanySeeder)->run();

    // Verify company exists without logo info
    $company = Company::where('name', 'Pioneering Evolution')->first();
    expect($company)->not->toBeNull();
    expect($company->logo_src)->toBeNull();
    expect($company->logo_alt)->toBeNull();
    expect($company->logo_display_name)->toBeFalse();
    expect($company->link)->toBeNull();

    // Now run ProjectSeeder which should UPDATE the company with logo info
    (new ProjectSeeder)->run();

    // Refresh and verify logo info was added
    $company->refresh();
    expect($company->logo_src)->toBe('pioneering-evolution.png');
    expect($company->logo_alt)->toBe('Pioneering Evolution Logo');
    expect($company->logo_display_name)->toBeTrue();
    expect($company->link)->toBe('https://www.pioneeringevolution.com/');
});

test('project seeder creates new companies with logo information if they do not exist', function () {
    // Don't run CompanySeeder, so companies don't exist yet
    // Run ProjectSeeder directly
    (new ProjectSeeder)->run();

    // Verify company was created with logo info
    $company = Company::where('name', 'Pioneering Evolution')->first();
    expect($company)->not->toBeNull();
    expect($company->logo_src)->toBe('pioneering-evolution.png');
    expect($company->logo_alt)->toBe('Pioneering Evolution Logo');
    expect($company->logo_display_name)->toBeTrue();
    expect($company->link)->toBe('https://www.pioneeringevolution.com/');
});

test('project seeder handles companies without logo information', function () {
    // Create a company without logo info manually
    Company::create(['name' => 'Test Company Without Logo']);

    // Create a minimal projects.json with a project for this company
    $projectsJsonPath = resource_path('js/data/projects.json');
    $originalContent = file_get_contents($projectsJsonPath);

    try {
        // Add a test project with company but no logo
        $testData = [
            [
                'id' => 'test-project',
                'title' => 'Test Project',
                'company' => [
                    'name' => 'Test Company Without Logo',
                ],
            ],
        ];
        file_put_contents($projectsJsonPath, json_encode($testData));

        (new ProjectSeeder)->run();

        // Verify company still exists and has null logo fields
        $company = Company::where('name', 'Test Company Without Logo')->first();
        expect($company)->not->toBeNull();
        expect($company->logo_src)->toBeNull();
        expect($company->logo_alt)->toBeNull();
        expect($company->logo_display_name)->toBeFalse();
        expect($company->link)->toBeNull();
    } finally {
        // Restore original file
        file_put_contents($projectsJsonPath, $originalContent);
    }
});
