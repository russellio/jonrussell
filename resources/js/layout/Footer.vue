<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const linkClass = 'font-medium text-slate-400 hover:text-teal-300 focus-visible:text-teal-300';
</script>

<template>
    <footer class="max-w-md pb-16 text-sm text-slate-500 sm:pb-0">
        <p>
            Designed and built by Jon Russell with
            <a href="https://vuejs.org/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Vue (opens in a new tab)">Vue</a>,
            <a href="https://laravel.com/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Laravel (opens in a new tab)"
                >Laravel</a
            >,
            <a href="https://inertiajs.com/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Inertia (opens in a new tab)"
                >Inertia</a
            >, and
            <a
                href="https://tailwindcss.com/"
                :class="linkClass"
                target="_blank"
                rel="noreferrer noopener"
                aria-label="Tailwind CSS (opens in a new tab)"
                >Tailwind CSS</a
            >, set in
            <a href="https://rsms.me/inter/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Inter (opens in a new tab)"
                >Inter</a
            >. Flip on space mode for a starfield powered by my open-source
            <Link href="/posts/introducing-vue-background-stars" :class="linkClass">vue-background-stars</Link>.
        </p>
    </footer>
</template>
