<?php

use App\Models\Company;
use App\Models\Position;
use App\Models\Skill;
use App\Models\SkillType;

test('timeline api returns positions ordered by start date descending', function () {
    $company = Company::factory()->create(['name' => 'Corp']);

    Position::factory()->create(['company_id' => $company->id, 'title' => 'Junior Dev', 'start_date' => '2018-01-01', 'end_date' => '2020-01-01']);
    Position::factory()->create(['company_id' => $company->id, 'title' => 'Senior Dev', 'start_date' => '2022-01-01', 'end_date' => '2024-01-01']);
    Position::factory()->create(['company_id' => $company->id, 'title' => 'Mid Dev', 'start_date' => '2020-01-01', 'end_date' => '2022-01-01']);

    $response = $this->getJson('/api/timeline');

    $response->assertOk()->assertJson(['success' => true]);

    $data = $response->json('data');
    expect($data)->toHaveCount(3);
    expect($data[0]['title'])->toBe('Senior Dev');
    expect($data[1]['title'])->toBe('Mid Dev');
    expect($data[2]['title'])->toBe('Junior Dev');
});

test('timeline api marks current position when end date is null', function () {
    $company = Company::factory()->create(['name' => 'Corp']);

    Position::factory()->create(['company_id' => $company->id, 'title' => 'Current Role', 'start_date' => '2024-01-01', 'end_date' => null]);
    Position::factory()->create(['company_id' => $company->id, 'title' => 'Past Role', 'start_date' => '2020-01-01', 'end_date' => '2023-12-31']);

    $response = $this->getJson('/api/timeline');

    $data = $response->json('data');
    $current = collect($data)->firstWhere('title', 'Current Role');
    $past = collect($data)->firstWhere('title', 'Past Role');

    expect($current['isCurrent'])->toBeTrue();
    expect($current['endDate'])->toBeNull();
    expect($past['isCurrent'])->toBeFalse();
    expect($past['endDate'])->toBe('2023-12-31');
});

test('timeline api includes company data', function () {
    $company = Company::factory()->create([
        'name' => 'Acme',
        'logo_src' => 'acme.png',
        'logo_alt' => 'Acme Logo',
        'logo_display_name' => true,
        'link' => 'https://acme.com',
        'description' => 'Explosives, Anvils — $1M/yr',
    ]);

    Position::factory()->create(['company_id' => $company->id, 'title' => 'Engineer', 'start_date' => '2023-01-01']);

    $response = $this->getJson('/api/timeline');

    $data = $response->json('data');
    expect($data[0]['company']['name'])->toBe('Acme');
    expect($data[0]['company']['logo']['src'])->toBe('acme.png');
    expect($data[0]['company']['logo']['alt'])->toBe('Acme Logo');
    expect($data[0]['company']['logo']['displayName'])->toBeTrue();
    expect($data[0]['company']['link'])->toBe('https://acme.com');
    expect($data[0]['company']['description'])->toBe('Explosives, Anvils — $1M/yr');
});

test('timeline api includes skills for each position', function () {
    $company = Company::factory()->create(['name' => 'Corp']);
    $skillType = SkillType::factory()->create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    $skill = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);

    $position = Position::factory()->create(['company_id' => $company->id, 'title' => 'Dev', 'start_date' => '2023-01-01']);
    $position->skills()->attach($skill->id);

    $response = $this->getJson('/api/timeline');

    $data = $response->json('data');
    expect($data[0]['skills'])->toHaveCount(1);
    expect($data[0]['skills'][0]['name'])->toBe('Laravel');
    expect($data[0]['skills'][0]['id'])->toBe($skill->id);
});

test('timeline api includes months for a position', function () {
    $company = Company::factory()->create(['name' => 'Corp']);
    Position::factory()->create(['company_id' => $company->id, 'title' => 'Dev', 'start_date' => '2023-01-01', 'end_date' => '2023-07-01']);

    $response = $this->getJson('/api/timeline');

    $data = $response->json('data');
    expect($data[0]['months'])->toBe(6);
});

test('timeline api returns formatted start and end dates', function () {
    $company = Company::factory()->create(['name' => 'Corp']);
    Position::factory()->create(['company_id' => $company->id, 'title' => 'Dev', 'start_date' => '2022-03-15', 'end_date' => '2024-09-30']);

    $response = $this->getJson('/api/timeline');

    $data = $response->json('data');
    expect($data[0]['startDate'])->toBe('2022-03-15');
    expect($data[0]['endDate'])->toBe('2024-09-30');
});

test('timeline api returns empty array when no positions exist', function () {
    $response = $this->getJson('/api/timeline');

    $response->assertOk()->assertJson(['success' => true, 'data' => []]);
});
