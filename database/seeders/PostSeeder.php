<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::updateOrCreate(
            ['slug' => 'introducing-vue-background-stars'],
            [
                'title' => 'Introducing vue-background-stars',
                'excerpt' => 'An open-source Vue 3 component that renders an animated starfield — the same one powering the space-mode toggle on this site.',
                'body' => '<p>Placeholder body — full launch post to follow.</p>',
                'image_src' => '/images/writing/vue-background-stars.png',
                'image_alt' => 'vue-background-stars animated starfield',
                'external_url' => null,
                'published_at' => now(),
                'order' => 0,
            ]
        );
    }
}
