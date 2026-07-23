<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'excerpt' => $this->faker->paragraph(),
            'body' => $this->faker->paragraph(),
            'image_src' => null,
            'image_alt' => null,
            'external_url' => null,
            'published_at' => now(),
            'order' => 0,
        ];
    }

    /**
     * Indicate that the post is not published.
     */
    public function unpublished(): static
    {
        return $this->state([
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the post links out to an external URL instead of a body.
     */
    public function external(): static
    {
        return $this->state([
            'body' => null,
            'external_url' => $this->faker->url(),
        ]);
    }

    /**
     * Indicate that the post has a full body and no external URL.
     */
    public function withBody(): static
    {
        return $this->state([
            'body' => $this->faker->paragraphs(3, true),
            'external_url' => null,
        ]);
    }
}
