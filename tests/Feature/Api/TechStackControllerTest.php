<?php

use App\Models\Icon;
use App\Models\TechStackItem;

test('tech stack api returns all items ordered by order field', function () {
    TechStackItem::factory()->create(['name' => 'PHP', 'percent' => 90, 'active' => true, 'order' => 2]);
    TechStackItem::factory()->create(['name' => 'Vue', 'percent' => 80, 'active' => true, 'order' => 1]);
    TechStackItem::factory()->create(['name' => 'MySQL', 'percent' => 70, 'active' => false, 'order' => 3]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()->assertJson(['success' => true]);

    $data = $response->json('data');
    expect($data)->toHaveCount(3);
    expect($data[0]['tech'])->toBe('Vue');
    expect($data[1]['tech'])->toBe('PHP');
    expect($data[2]['tech'])->toBe('MySQL');
});

test('tech stack api returns icon type and name from associated icon', function () {
    $icon = Icon::factory()->create(['icon_type' => 'si', 'icon_name' => 'laravel']);
    TechStackItem::factory()->create(['name' => 'Laravel', 'percent' => 95, 'active' => true, 'order' => 0, 'icon_id' => $icon->id]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()
        ->assertJsonPath('data.0.iconType', 'si')
        ->assertJsonPath('data.0.iconName', 'laravel');
});

test('tech stack api returns null icon fields when no icon associated', function () {
    TechStackItem::factory()->create(['name' => 'Misc', 'percent' => 50, 'order' => 0]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()
        ->assertJsonPath('data.0.iconType', null)
        ->assertJsonPath('data.0.iconName', null);
});

test('tech stack api returns percent as string', function () {
    TechStackItem::factory()->create(['name' => 'TypeScript', 'percent' => 75, 'active' => true, 'order' => 0]);

    $response = $this->getJson('/api/tech-stack');

    $data = $response->json('data');
    expect($data[0]['percent'])->toBe('75');
});

test('tech stack api returns active flag', function () {
    TechStackItem::factory()->create(['name' => 'Active Tech', 'percent' => 60, 'active' => true, 'order' => 0]);
    TechStackItem::factory()->create(['name' => 'Inactive Tech', 'percent' => 40, 'active' => false, 'order' => 1]);

    $response = $this->getJson('/api/tech-stack');

    $data = $response->json('data');
    expect($data[0]['active'])->toBeTrue();
    expect($data[1]['active'])->toBeFalse();
});

test('tech stack api returns empty array when no items exist', function () {
    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()->assertJson(['success' => true, 'data' => []]);
});
