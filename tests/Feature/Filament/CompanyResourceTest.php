<?php

use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\CompanyResource\Pages\CreateCompany;
use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $admin = User::factory()->create(['email' => 'admin@example.com']);
    config(['app.admin_emails' => ['admin@example.com']]);
    $this->actingAs($admin);
});

test('company create page renders', function () {
    $this->get(CompanyResource::getUrl('create'))->assertSuccessful();
});

test('a company can be created with logo, link, and description fields', function () {
    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => 'Acme Corp',
            'logo_src' => 'acme.png',
            'logo_alt' => 'Acme logo',
            'logo_display_name' => true,
            'link' => 'https://acme.example.com',
            'description' => 'Widgets and gizmos.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Company::class, [
        'name' => 'Acme Corp',
        'logo_src' => 'acme.png',
        'logo_alt' => 'Acme logo',
        'logo_display_name' => true,
        'link' => 'https://acme.example.com',
        'description' => 'Widgets and gizmos.',
    ]);
});

test('editing a company loads its existing logo and link fields', function () {
    $company = Company::factory()->create([
        'logo_src' => 'existing.png',
        'logo_alt' => 'Existing logo',
        'logo_display_name' => false,
        'link' => 'https://existing.example.com',
        'description' => 'Existing description.',
    ]);

    Livewire::test(EditCompany::class, ['record' => $company->getRouteKey()])
        ->assertFormSet([
            'logo_src' => 'existing.png',
            'logo_alt' => 'Existing logo',
            'logo_display_name' => false,
            'link' => 'https://existing.example.com',
            'description' => 'Existing description.',
        ]);
});
