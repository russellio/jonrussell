<?php

use App\Models\Icon;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;

test('icon valid types constant contains fa and si', function () {
    expect(Icon::VALID_ICON_TYPES)->toBe(['fa', 'si']);
});

test('icon accepts fa type', function () {
    $icon = Icon::create(['icon_type' => 'fa', 'icon_name' => 'laravel']);

    expect($icon->icon_type)->toBe('fa');
    expect($icon->icon_name)->toBe('laravel');
});

test('icon accepts si type', function () {
    $icon = Icon::create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);

    expect($icon->icon_type)->toBe('si');
});


test('icon throws for invalid type', function () {
    expect(fn () => Icon::create(['icon_type' => 'invalid', 'icon_name' => 'test']))
        ->toThrow(\InvalidArgumentException::class, 'Invalid icon_type: invalid');
});

test('icon has many tech stack items', function () {
    $icon = Icon::create(['icon_type' => 'fa', 'icon_name' => 'php']);
    foreach ([['name' => 'PHP', 'order' => 0], ['name' => 'PHP-CLI', 'order' => 1]] as $attrs) {
        (new TechStackItem)->forceFill(array_merge(['icon_type' => 'fa', 'icon_name' => 'php', 'percent' => 80, 'active' => true, 'icon_id' => $icon->id], $attrs))->save();
    }

    expect($icon->techStackItems)->toHaveCount(2);
});

test('icon has many skills', function () {
    $icon = Icon::create(['icon_type' => 'si', 'icon_name' => 'vuedotjs']);
    $skillType = SkillType::create(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 0]);
    Skill::create(['skill_type_id' => $skillType->id, 'name' => 'Vue.js', 'order' => 0, 'icon_id' => $icon->id]);
    Skill::create(['skill_type_id' => $skillType->id, 'name' => 'Vue Router', 'order' => 1, 'icon_id' => $icon->id]);

    expect($icon->skills)->toHaveCount(2);
});
