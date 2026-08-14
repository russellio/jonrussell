<?php

use App\Models\Icon;
use App\Models\Skill;
use App\Models\SkillType;

test('skill belongs to skill type', function () {
    $skillType = SkillType::factory()->create(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 0]);
    $skill = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue.js', 'order' => 0]);

    expect($skill->skillType)->toBeInstanceOf(SkillType::class);
    expect($skill->skillType->id)->toBe($skillType->id);
});

test('skill belongs to icon', function () {
    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'vuedotjs']);
    $skillType = SkillType::factory()->create(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 0]);
    $skill = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue.js', 'order' => 0, 'icon_id' => $icon->id]);

    expect($skill->icon)->toBeInstanceOf(Icon::class);
    expect($skill->icon->id)->toBe($icon->id);
});

test('skill icon is null when no icon assigned', function () {
    $skillType = SkillType::factory()->create(['name' => 'Other', 'slug' => 'other', 'order' => 0]);
    $skill = Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Misc', 'order' => 0]);

    expect($skill->icon)->toBeNull();
});
