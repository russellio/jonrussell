<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectKeyTakeaway;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectKeyTakeawayFactory extends Factory
{
    protected $model = ProjectKeyTakeaway::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
