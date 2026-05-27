<?php

use App\Models\Company;
use App\Models\Icon;
use App\Models\Position;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;

// icon_type and icon_name are legacy NOT NULL columns not in $fillable; use forceFill to satisfy the schema.
function techItem(array $attrs): TechStackItem
{
    $item = (new TechStackItem)->forceFill(array_merge([
        'icon_type' => 'fa',
        'icon_name' => 'code',
        'active' => false,
        'order' => 0,
    ], $attrs));
    $item->save();

    return $item;
}

test('tech stack item belongs to skill', function () {
    $skillType = SkillType::create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    $skill = Skill::create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);
    $item = techItem(['name' => 'Laravel', 'percent' => 90, 'skill_id' => $skill->id]);

    expect($item->skill)->toBeInstanceOf(Skill::class);
    expect($item->skill->id)->toBe($skill->id);
});

test('tech stack item belongs to icon', function () {
    $icon = Icon::create(['icon_type' => 'fa', 'icon_name' => 'laravel']);
    $item = techItem(['name' => 'Laravel', 'percent' => 90, 'icon_id' => $icon->id]);

    expect($item->icon)->toBeInstanceOf(Icon::class);
    expect($item->icon->id)->toBe($icon->id);
});

test('icon type accessor returns type from associated icon', function () {
    $icon = Icon::create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);
    $item = techItem(['name' => 'Vue', 'percent' => 80, 'icon_id' => $icon->id]);

    expect($item->icon_type)->toBe('si');
});

test('icon name accessor returns name from associated icon', function () {
    $icon = Icon::create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);
    $item = techItem(['name' => 'Vue', 'percent' => 80, 'icon_id' => $icon->id]);

    expect($item->icon_name)->toBe('vuedotjs');
});

test('icon type accessor returns null when no icon is associated', function () {
    $item = techItem(['name' => 'Misc', 'percent' => 50]);

    expect($item->icon_type)->toBeNull();
});

test('icon name accessor returns null when no icon is associated', function () {
    $item = techItem(['name' => 'Misc', 'percent' => 50]);

    expect($item->icon_name)->toBeNull();
});

test('calculated percent returns manual percent when set', function () {
    $item = techItem(['name' => 'PHP', 'percent' => 85]);

    expect($item->calculated_percent)->toBe(85);
});

test('calculated percent returns zero when no skill and no manual percent', function () {
    $item = techItem(['name' => 'Unknown']);

    expect($item->calculated_percent)->toBe(0);
});

test('calculated percent derives from total position months when no manual percent', function () {
    $skillType = SkillType::create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    $skill = Skill::create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);

    $company = Company::create(['name' => 'Corp']);
    $position = Position::create([
        'company_id' => $company->id,
        'title' => 'Dev',
        'start_date' => '2023-01-01',
        'end_date' => '2024-07-01', // 18 months
    ]);
    $position->skills()->attach($skill->id);

    $item = techItem(['name' => 'Laravel', 'active' => true, 'skill_id' => $skill->id]);

    // round(18 / 180 * 100) = 10
    expect($item->calculated_percent)->toBe(10);
});

test('calculated percent is capped at 100', function () {
    $skillType = SkillType::create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    $skill = Skill::create(['skill_type_id' => $skillType->id, 'name' => 'PHP', 'order' => 0]);

    $company = Company::create(['name' => 'Corp']);
    $position = Position::create([
        'company_id' => $company->id,
        'title' => 'Dev',
        'start_date' => '2005-01-01',
        'end_date' => '2025-01-01', // 240 months — exceeds MAX_EXPECTED_MONTHS
    ]);
    $position->skills()->attach($skill->id);

    $item = techItem(['name' => 'PHP', 'active' => true, 'skill_id' => $skill->id]);

    expect($item->calculated_percent)->toBe(100);
});

test('active is cast to boolean', function () {
    $active = techItem(['name' => 'A', 'percent' => 50, 'active' => 1]);
    $inactive = techItem(['name' => 'B', 'percent' => 50, 'active' => 0, 'order' => 1]);

    expect($active->active)->toBeTrue()->toBeBool();
    expect($inactive->active)->toBeFalse()->toBeBool();
});
