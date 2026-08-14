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
            'icon_type' => $this->faker->randomElement(['lucide', 'simple-icons']),
            'icon_name' => $this->faker->word(),
        ];
    }

    public function lucide(): static
    {
        return $this->state([
            'icon_type' => 'lucide',
            'icon_name' => 'code',
        ]);
    }

    public function simpleIcon(): static
    {
        return $this->state([
            'icon_type' => 'simple-icons',
            'icon_name' => 'github',
        ]);
    }
}
