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
            'label' => $this->faker->word(),
            'href' => $this->faker->url(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
