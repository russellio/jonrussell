<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'logo_src' => $this->faker->url(),
            'logo_alt' => $this->faker->sentence(),
            'logo_display_name' => $this->faker->boolean(),
            'link' => $this->faker->url(),
            'description' => $this->faker->sentence(),
        ];
    }
}
