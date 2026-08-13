<?php

use App\Filament\Resources\PositionResource;
use App\Filament\Resources\PositionResource\Pages\CreatePosition;
use App\Filament\Resources\PositionResource\Pages\EditPosition;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $admin = User::factory()->create(['email' => 'admin@example.com']);
    config(['app.admin_emails' => ['admin@example.com']]);
    $this->actingAs($admin);
});

test('position create page renders', function () {
    $this->get(PositionResource::getUrl('create'))->assertSuccessful();
});

test('a position can be created with a description, closing the missing-field gap', function () {
    $company = Company::factory()->create();

    Livewire::test(CreatePosition::class)
        ->fillForm([
            'company_id' => $company->id,
            'title' => 'Staff Engineer',
            'description' => '<p>Led the thing.</p>',
            'start_date' => '2024-01-01',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Position::class, [
        'title' => 'Staff Engineer',
        'description' => '<p>Led the thing.</p>',
    ]);
});

test('editing a position loads its existing description', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create([
        'company_id' => $company->id,
        'description' => '<p>Existing description</p>',
    ]);

    Livewire::test(EditPosition::class, ['record' => $position->getRouteKey()])
        ->assertFormSet(['description' => '<p>Existing description</p>']);
});
