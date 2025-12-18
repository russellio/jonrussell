<?php

use App\Models\Company;

test('company has correct fillable attributes', function () {
    $fillable = (new Company)->getFillable();

    expect($fillable)->toContain('name');
    expect($fillable)->toContain('logo_src');
    expect($fillable)->toContain('logo_alt');
    expect($fillable)->toContain('logo_display_name');
    expect($fillable)->toContain('link');
});

test('logo_display_name is cast to boolean', function () {
    $company = Company::create([
        'name' => 'Test Company',
        'logo_src' => '/path/to/logo.png',
        'logo_alt' => 'Company Logo',
        'logo_display_name' => 1,
        'link' => 'https://example.com',
    ]);

    expect($company->logo_display_name)->toBeTrue();
    expect($company->logo_display_name)->toBeBool();

    $company->update(['logo_display_name' => 0]);
    $company->refresh();

    expect($company->logo_display_name)->toBeFalse();
    expect($company->logo_display_name)->toBeBool();
});

test('logo_display_name is cast correctly when explicitly set', function () {
    $company = Company::create([
        'name' => 'Test Company',
        'logo_display_name' => false,
    ]);

    expect($company->logo_display_name)->toBeFalse();
    expect($company->logo_display_name)->toBeBool();
});

test('company can store logo and link information', function () {
    $company = Company::create([
        'name' => 'Test Company',
        'logo_src' => '/images/logos/test.png',
        'logo_alt' => 'Test Company Logo',
        'logo_display_name' => true,
        'link' => 'https://test.com',
    ]);

    expect($company->name)->toBe('Test Company');
    expect($company->logo_src)->toBe('/images/logos/test.png');
    expect($company->logo_alt)->toBe('Test Company Logo');
    expect($company->logo_display_name)->toBeTrue();
    expect($company->link)->toBe('https://test.com');
});
