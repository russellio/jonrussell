<?php

namespace Database\Factories;

use App\Models\Icon;
use Illuminate\Database\Eloquent\Factories\Factory;

class IconFactory extends Factory
{
    protected $model = Icon::class;

    public function definition(): array
    {
        return [
            'icon_type' => $this->faker->randomElement(['fa', 'si']),
            'icon_name' => $this->faker->word(),
        ];
    }

    public function fontAwesome(): static
    {
        return $this->state([
            'icon_type' => 'fa',
            'icon_name' => 'code',
        ]);
    }

    public function simpleIcon(): static
    {
        return $this->state([
            'icon_type' => 'si',
            'icon_name' => 'github',
        ]);
    }
}
