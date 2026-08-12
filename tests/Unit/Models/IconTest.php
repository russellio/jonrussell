<?php

use App\Models\Icon;
use App\Models\Skill;
use App\Models\SkillType;
use App\Models\TechStackItem;

test('icon valid types constant contains lucide and simple-icons', function () {
    expect(Icon::VALID_ICON_TYPES)->toBe(['lucide', 'simple-icons']);
});

test('icon accepts lucide type', function () {
    $icon = Icon::factory()->create(['icon_type' => 'lucide', 'icon_name' => 'code']);

    expect($icon->icon_type)->toBe('lucide');
    expect($icon->icon_name)->toBe('code');
});

test('icon accepts simple-icons type', function () {
    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'vuedotjs']);

    expect($icon->icon_type)->toBe('simple-icons');
});

test('icon throws for invalid type', function () {
    expect(fn () => Icon::factory()->create(['icon_type' => 'invalid', 'icon_name' => 'test']))
        ->toThrow(InvalidArgumentException::class, 'Invalid icon_type: invalid');
});

test('icon has many tech stack items', function () {
    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'php']);
    TechStackItem::factory()->create(['icon_id' => $icon->id, 'name' => 'PHP', 'order' => 0]);
    TechStackItem::factory()->create(['icon_id' => $icon->id, 'name' => 'PHP-CLI', 'order' => 1]);

    expect($icon->techStackItems)->toHaveCount(2);
});

test('icon has many skills', function () {
    $icon = Icon::factory()->create(['icon_type' => 'simple-icons', 'icon_name' => 'vuedotjs']);
    $skillType = SkillType::factory()->create(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 0]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue.js', 'order' => 0, 'icon_id' => $icon->id]);
    Skill::factory()->create(['skill_type_id' => $skillType->id, 'name' => 'Vue Router', 'order' => 1, 'icon_id' => $icon->id]);

    expect($icon->skills)->toHaveCount(2);
});
