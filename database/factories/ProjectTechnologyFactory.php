<?php

namespace Database\Factories;

use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectTechnology;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectTechnologyFactory extends Factory
{
    protected $model = ProjectTechnology::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->word(),
            'icon_id' => null,
            'is_highlighted' => $this->faker->boolean(),
            'order' => $this->faker->numberBetween(0, 15),
        ];
    }

    public function withIcon(): static
    {
        return $this->state([
            'icon_id' => Icon::factory(),
        ]);
    }

    public function highlighted(): static
    {
        return $this->state([
            'is_highlighted' => true,
        ]);
    }
}
