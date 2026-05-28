<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, 'now');

        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }

    public function current(): static
    {
        return $this->state([
            'end_date' => null,
            'start_date' => now()->subMonths(6)->format('Y-m-d'),
        ]);
    }
}
