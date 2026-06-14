<?php

use App\Models\Icon;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;

test('icon valid types constant contains fa and si', function () {
    expect(Icon::VALID_ICON_TYPES)->toBe(['fa', 'si']);
});

test('icon accepts fa type', function () {
    $icon = Icon::factory()->create(['icon_type' => 'fa', 'icon_name' => 'laravel']);

    expect($icon->icon_type)->toBe('fa');
    expect($icon->icon_name)->toBe('laravel');
});

test('icon accepts si type', function () {
    $icon = Icon::factory()->create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);

    expect($icon->icon_type)->toBe('si');
});

test('icon throws for invalid type', function () {
    expect(fn () => Icon::factory()->create(['icon_type' => 'invalid', 'icon_name' => 'test']))
        ->toThrow(InvalidArgumentException::class, 'Invalid icon_type: invalid');
});

test('icon has many tech stack items', function () {
    $icon = Icon::factory()->create(['icon_type' => 'fa', 'icon_name' => 'php']);
    TechStackItem::factory()->create(['icon_id' => $icon->id, 'name' => 'PHP', 'order' => 0]);
    TechStackItem::factory()->create(['icon_id' => $icon->id, 'name' => 'PHP-CLI', 'order' => 1]);

    expect($icon->techStackItems)->toHaveCount(2);
});

test('icon has many skills', function () {
    $icon = Icon::factory()->create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);
    $skillType = SkillType::factory()->create(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue.js', 'order' => 0, 'icon_id' => $icon->id]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue Router', 'order' => 1, 'icon_id' => $icon->id]);

    expect($icon->skills)->toHaveCount(2);
});
