<?php

namespace Database\Factories;

use App\Models\Icon;
use App\Models\Skill;
use App\Models\TechStackItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechStackItemFactory extends Factory
{
    protected $model = TechStackItem::class;

    public function definition(): array
    {
        return [
            'skill_id' => null,
            'name' => $this->faker->word(),
            'percent' => $this->faker->numberBetween(50, 100),
            'icon_id' => null,
            'active' => true,
            'order' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function withSkill(): static
    {
        return $this->state([
            'skill_id' => Skill::factory(),
        ]);
    }

    public function withIcon(): static
    {
        return $this->state([
            'icon_id' => Icon::factory(),
        ]);
    }

    public function fontAwesomeIcon(): static
    {
        return $this->state([
            'icon_id' => Icon::factory()->fontAwesome(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state([
            'active' => false,
        ]);
    }

    public function noPercent(): static
    {
        return $this->state([
            'percent' => null,
        ]);
    }
}
