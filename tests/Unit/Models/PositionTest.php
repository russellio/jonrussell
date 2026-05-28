<?php

use App\Models\Company;
use App\Models\Position;
use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Support\Carbon;

test('position belongs to company', function () {
    $company = Company::factory()->create(['name' => 'Acme']);
    $position = Position::factory()->create(['company_id' => $company->id, 'title' => 'Engineer', 'start_date' => '2023-01-01']);

    expect($position->company)->toBeInstanceOf(Company::class);
    expect($position->company->id)->toBe($company->id);
});

test('position has many to many skills', function () {
    $company = Company::factory()->create(['name' => 'Acme']);
    $skillType = SkillType::factory()->create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    $skill1 = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);
    $skill2 = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'PHP', 'order' => 1]);

    $position = Position::factory()->create(['company_id' => $company->id, 'title' => 'Dev', 'start_date' => '2023-01-01']);
    $position->skills()->attach([$skill1->id, $skill2->id]);

    expect($position->skills)->toHaveCount(2);
    expect($position->skills->pluck('name')->toArray())->toContain('Laravel');
    expect($position->skills->pluck('name')->toArray())->toContain('PHP');
});

test('months attribute returns correct count for a past position', function () {
    $company = Company::factory()->create(['name' => 'Acme']);
    $position = Position::factory()->create([
        'company_id' => $company->id,
        'title' => 'Dev',
        'start_date' => '2023-01-01',
        'end_date' => '2023-07-01',
    ]);

    expect($position->months)->toBe(6);
});

test('months attribute uses current date when end date is null', function () {
    $company = Company::factory()->create(['name' => 'Acme']);

    try {
        Carbon::setTestNow('2025-05-01');
        $position = Position::factory()->create([
            'company_id' => $company->id,
            'title' => 'Dev',
            'start_date' => '2025-01-01',
            'end_date' => null,
        ]);

        expect($position->months)->toBe(4);
    } finally {
        Carbon::setTestNow();
    }
});

test('start and end dates are cast to carbon instances', function () {
    $company = Company::factory()->create(['name' => 'Acme']);
    $position = Position::factory()->create([
        'company_id' => $company->id,
        'title' => 'Dev',
        'start_date' => '2023-01-01',
        'end_date' => '2024-01-01',
    ]);

    expect($position->start_date)->toBeInstanceOf(Carbon::class);
    expect($position->end_date)->toBeInstanceOf(Carbon::class);
});

test('end date cast is null when not set', function () {
    $company = Company::factory()->create(['name' => 'Acme']);
    $position = Position::factory()->create([
        'company_id' => $company->id,
        'title' => 'Dev',
        'start_date' => '2023-01-01',
        'end_date' => null,
    ]);

    expect($position->end_date)->toBeNull();
});
