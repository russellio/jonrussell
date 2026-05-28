<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectLinkFactory extends Factory
{
    protected $model = ProjectLink::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->word(),
            'url' => $this->faker->url(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
