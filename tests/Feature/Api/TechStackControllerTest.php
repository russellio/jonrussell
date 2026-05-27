<?php

use App\Models\Icon;
use App\Models\TechStackItem;

// icon_type and icon_name are legacy NOT NULL columns not in $fillable; use forceFill to satisfy the schema.
function makeStackItem(array $attrs): TechStackItem
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

test('tech stack api returns all items ordered by order field', function () {
    makeStackItem(['name' => 'PHP', 'percent' => 90, 'active' => true, 'order' => 2]);
    makeStackItem(['name' => 'Vue', 'percent' => 80, 'active' => true, 'order' => 1]);
    makeStackItem(['name' => 'MySQL', 'percent' => 70, 'order' => 3]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()->assertJson(['success' => true]);

    $data = $response->json('data');
    expect($data)->toHaveCount(3);
    expect($data[0]['tech'])->toBe('Vue');
    expect($data[1]['tech'])->toBe('PHP');
    expect($data[2]['tech'])->toBe('MySQL');
});

test('tech stack api returns icon type and name from associated icon', function () {
    $icon = Icon::create(['icon_type' => 'si', 'icon_name' => 'laravel']);
    makeStackItem(['name' => 'Laravel', 'percent' => 95, 'active' => true, 'order' => 0, 'icon_id' => $icon->id]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()
        ->assertJsonPath('data.0.iconType', 'si')
        ->assertJsonPath('data.0.iconName', 'laravel');
});

test('tech stack api returns null icon fields when no icon associated', function () {
    makeStackItem(['name' => 'Misc', 'percent' => 50, 'order' => 0]);

    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()
        ->assertJsonPath('data.0.iconType', null)
        ->assertJsonPath('data.0.iconName', null);
});

test('tech stack api returns percent as string', function () {
    makeStackItem(['name' => 'TypeScript', 'percent' => 75, 'active' => true, 'order' => 0]);

    $response = $this->getJson('/api/tech-stack');

    $data = $response->json('data');
    expect($data[0]['percent'])->toBe('75');
});

test('tech stack api returns active flag', function () {
    makeStackItem(['name' => 'Active Tech', 'percent' => 60, 'active' => true, 'order' => 0]);
    makeStackItem(['name' => 'Inactive Tech', 'percent' => 40, 'active' => false, 'order' => 1]);

    $response = $this->getJson('/api/tech-stack');

    $data = $response->json('data');
    expect($data[0]['active'])->toBeTrue();
    expect($data[1]['active'])->toBeFalse();
});

test('tech stack api returns empty array when no items exist', function () {
    $response = $this->getJson('/api/tech-stack');

    $response->assertOk()->assertJson(['success' => true, 'data' => []]);
});
