<?php

use App\Models\Skill;
use App\Models\SkillType;

test('skill type has many skills', function () {
    $skillType = SkillType::factory()->create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'PHP', 'order' => 1]);

    expect($skillType->skills)->toHaveCount(2);
});

test('skill type skills are ordered by order field', function () {
    $skillType = SkillType::factory()->create(['name' => 'Backend', 'slug' => 'backend', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'PHP', 'order' => 2]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Laravel', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'MySQL', 'order' => 1]);

    $names = $skillType->skills->pluck('name')->toArray();
    expect($names)->toBe(['Laravel', 'MySQL', 'PHP']);
});

test('skill type with no skills returns empty collection', function () {
    $skillType = SkillType::factory()->create(['name' => 'Empty', 'slug' => 'empty', 'order' => 0]);

    expect($skillType->skills)->toBeEmpty();
});
