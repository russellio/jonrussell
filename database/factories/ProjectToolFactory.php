<?php

namespace Database\Factories;

use App\Models\Icon;
use App\Models\Project;
use App\Models\ProjectTool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectToolFactory extends Factory
{
    protected $model = ProjectTool::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->word(),
            'icon_id' => null,
            'order' => $this->faker->numberBetween(0, 15),
        ];
    }

    public function withIcon(): static
    {
        return $this->state([
            'icon_id' => Icon::factory(),
        ]);
    }
}
