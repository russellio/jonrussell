<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $body = <<<'HTML'
        <p>Flip on <strong>space mode</strong> in the corner of this site and the background fills with a slow-drifting starfield. That effect is a Vue 3 component I pulled out into its own open-source package, <a href="https://www.npmjs.com/package/@russellio/vue-background-stars" target="_blank" rel="noreferrer noopener">@russellio/vue-background-stars</a>. This site is its live demo.</p>
        <h2>Why I built it</h2>
        <p>I wanted a night-sky background I could drop into a Vue app without pulling in a canvas renderer or an animation library for something that is, at the end of the day, decorative. Most starfield snippets floating around are one-off CodePens welded to a specific layout. I wanted a real component: typed props, reduced-motion support, and a toggle &mdash; something I could reuse and that others could just install.</p>
        <h2>How it works</h2>
        <p>Instead of a canvas, the stars are generated as several <code>box-shadow</code> layers over a single coordinate space. That keeps the DOM tiny while still allowing a thousand-plus stars at the "dense" setting. The layers are built once on mount inside <code>requestAnimationFrame</code>, and the blink animation honors <code>prefers-reduced-motion</code> (or you can disable it outright with a prop). No runtime dependencies beyond Vue.</p>
        <h2>Using it</h2>
        <pre><code>npm install @russellio/vue-background-stars</code></pre>
        <pre><code>&lt;script setup lang="ts"&gt;
        import { BackgroundStars } from '@russellio/vue-background-stars';
        import '@russellio/vue-background-stars/style.css';
        &lt;/script&gt;

        &lt;template&gt;
          &lt;BackgroundStars /&gt;
        &lt;/template&gt;</code></pre>
        <p>It renders as a fixed background at <code>z-index: -1</code>, so page content sits on top as long as it establishes its own stacking context. Star count, density, color palette, and animation speed are all props, and there is an optional <code>ToggleSwitch</code> &mdash; that is the "space mode:" switch you see here, wired to a <code>localStorage</code>-backed preference.</p>
        <h2>Get it</h2>
        <ul>
          <li><a href="https://www.npmjs.com/package/@russellio/vue-background-stars" target="_blank" rel="noreferrer noopener">npm: @russellio/vue-background-stars</a></li>
          <li><a href="https://github.com/russellio/vue-background-stars" target="_blank" rel="noreferrer noopener">GitHub source</a></li>
          <li><a href="https://russellio.github.io/vue-background-stars/" target="_blank" rel="noreferrer noopener">Live demo</a></li>
        </ul>
        HTML;

        Post::updateOrCreate(
            ['slug' => 'introducing-vue-background-stars'],
            [
                'title' => 'Introducing vue-background-stars',
                'excerpt' => 'A lightweight, dependency-free animated starfield for Vue 3 — the same component powering the space-mode toggle on this site.',
                'body' => $body,
                'image_src' => '/images/posts/vue-background-stars.png',
                'image_alt' => 'vue-background-stars animated starfield',
                'external_url' => null,
                'published_at' => now(),
                'order' => 0,
            ]
        );
    }
}
