<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->words(3, true);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'byline' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'primary_image_src' => $this->faker->imageUrl(),
            'primary_image_title' => $this->faker->sentence(),
            'primary_image_alt' => $this->faker->sentence(),
            'bg_image' => $this->faker->imageUrl(),
            'bg_position_x' => $this->faker->randomElement(['left', 'center', 'right']),
            'bg_position_y' => $this->faker->randomElement(['top', 'center', 'bottom']),
            'company_id' => null,
            'order' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function withCompany(): static
    {
        return $this->state([
            'company_id' => Company::factory(),
        ]);
    }
}
