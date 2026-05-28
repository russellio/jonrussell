<?php

namespace Database\Factories;

use App\Models\Icon;
use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'skill_type_id' => SkillType::factory(),
            'name' => $this->faker->word(),
            'order' => $this->faker->numberBetween(0, 20),
            'icon_id' => Icon::factory(),
        ];
    }
}
