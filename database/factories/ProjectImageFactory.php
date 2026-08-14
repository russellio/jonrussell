<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectImageFactory extends Factory
{
    protected $model = ProjectImage::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'src' => $this->faker->word().'.png',
            'title' => $this->faker->sentence(3),
            'alt' => $this->faker->sentence(3),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
