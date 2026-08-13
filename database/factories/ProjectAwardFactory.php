<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectAward;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectAwardFactory extends Factory
{
    protected $model = ProjectAward::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'text' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
