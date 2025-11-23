<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            SkillTypeSeeder::class,      // Creates skill types and skills (no dependencies)
            CompanySeeder::class,         // Creates companies (no dependencies)
            PositionSeeder::class,        // Creates positions (depends on Company)
            PositionSkillSeeder::class,   // Links skills to positions (depends on Position and Skill)
            TechStackItemSeeder::class,   // Creates tech stack items (depends on Skill)
        ]);
    }
}
