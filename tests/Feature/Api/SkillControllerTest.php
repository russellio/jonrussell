<?php

use App\Http\Controllers\Api\SkillController;
use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Http\JsonResponse;

test('skill controller returns all skills grouped by skill type', function () {
    // Create test data using database
    SkillType::factory()
        ->state(['name' => 'Frontend', 'slug' => 'frontend', 'order' => 1])
        ->has(Skill::factory()->state(['name' => 'Vue.js', 'order' => 0]))
        ->has(Skill::factory()->state(['name' => 'React', 'order' => 1]))
        ->create();

    SkillType::factory()
        ->state(['name' => 'Backend', 'slug' => 'backend', 'order' => 2])
        ->has(Skill::factory()->state(['name' => 'Laravel', 'order' => 0]))
        ->has(Skill::factory()->state(['name' => 'PHP', 'order' => 1]))
        ->create();

    $controller = new SkillController;
    $response = $controller->index();

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->status())->toBe(200);

    $json = json_decode($response->getContent(), true);
    expect($json)->toHaveKey('success', true);
    expect($json['success'])->toBeTrue();
    expect($json)->toHaveKey('data');
    expect($json['data'])->toBeArray();
    expect(count($json['data']))->toBe(2);

    // Check first skill type structure
    expect($json['data'][0])->toHaveKey('id');
    expect($json['data'][0])->toHaveKey('name', 'Frontend');
    expect($json['data'][0])->toHaveKey('slug', 'frontend');
    expect($json['data'][0])->toHaveKey('skills');
    expect($json['data'][0]['skills'])->toBeArray();
    expect(count($json['data'][0]['skills']))->toBe(2);

    // Check skill structure
    expect($json['data'][0]['skills'][0])->toHaveKey('id');
    expect($json['data'][0]['skills'][0])->toHaveKey('name');
    expect($json['data'][0]['skills'][0])->not->toHaveKey('order');
    expect($json['data'][0]['skills'][0])->not->toHaveKey('skill_type_id');

    // Check second skill type structure
    expect($json['data'][1])->toHaveKey('id');
    expect($json['data'][1])->toHaveKey('name', 'Backend');
    expect($json['data'][1])->toHaveKey('slug', 'backend');
    expect($json['data'][1])->toHaveKey('skills');
    expect(count($json['data'][1]['skills']))->toBe(2);
});

test('skill controller returns empty array when no skill types exist', function () {
    $controller = new SkillController;
    $response = $controller->index();

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->status())->toBe(200);

    $json = json_decode($response->getContent(), true);
    expect($json)->toHaveKey('success', true);
    expect($json)->toHaveKey('data');
    expect($json['data'])->toBeArray();
    expect($json['data'])->toBeEmpty();
});

test('skill controller returns skill type without skills correctly', function () {
    $skillType = SkillType::factory()->create([
        'name' => 'Frontend',
        'slug' => 'frontend',
        'order' => 1,
    ]);

    $controller = new SkillController;
    $response = $controller->index();

    $json = json_decode($response->getContent(), true);
    expect($json['data'])->toBeArray();

    // Find the skill type we just created
    $foundSkillType = collect($json['data'])->firstWhere('id', $skillType->id);
    expect($foundSkillType)->not->toBeNull();
    expect($foundSkillType)->toHaveKey('skills');
    expect($foundSkillType['skills'])->toBeArray();
    expect($foundSkillType['skills'])->toBeEmpty();
});

test('skill controller orders skill types by order then name', function () {
    SkillType::factory()->create(['name' => 'Z-Type', 'slug' => 'z-type', 'order' => 2]);
    SkillType::factory()->create(['name' => 'A-Type', 'slug' => 'a-type', 'order' => 1]);
    SkillType::factory()->create(['name' => 'B-Type', 'slug' => 'b-type', 'order' => 1]);

    $controller = new SkillController;
    $response = $controller->index();

    $json = json_decode($response->getContent(), true);
    expect($json['data'][0]['name'])->toBe('A-Type');
    expect($json['data'][1]['name'])->toBe('B-Type');
    expect($json['data'][2]['name'])->toBe('Z-Type');
});
