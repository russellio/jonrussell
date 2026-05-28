<?php

namespace Database\Factories;

use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SkillTypeFactory extends Factory
{
    protected $model = SkillType::class;

    public function definition(): array
    {
        $name = $this->faker->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
