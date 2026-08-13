<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const linkClass = 'font-medium text-slate-400 hover:text-blue-300 focus-visible:text-blue-300';

const currentYear = () => {
    return new Date().getFullYear();
};
</script>

<template>
    <footer class="w-full pb-16 text-sm text-slate-500 sm:pb-0">
        <p class="mb-2 text-justify">
            Designed and built by Jon Russell with
            <a href="https://vuejs.org/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Vue (opens in a new tab)">Vue 3</a>,
            <a href="https://ui.nuxt.com/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Vue (opens in a new tab)">Nuxt</a
            >,
            <a href="https://laravel.com/" :class="linkClass" target="_blank" rel="noreferrer noopener" aria-label="Laravel (opens in a new tab)"
                >Laravel 13</a
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
            <a
                href="https://github.com/notofonts/latin-greek-cyrillic"
                :class="linkClass"
                target="_blank"
                rel="noopener"
                aria-label="Noto Sans (opens in a new tab)"
                >Noto Sans</a
            >,
            <a
                href="https://github.com/googlefonts/spacemono"
                :class="linkClass"
                target="_blank"
                rel="noreferrer noopener"
                aria-label="Space Mono (opens in a new tab)"
                >Space Mono</a
            >, and
            <a
                href="https://jenskutilek.github.io/homecomputer-fonts/documentation/demo-sixtyfour.html"
                :class="linkClass"
                target="_blank"
                rel="noreferrer noopener"
                aria-label="SixtyFour (opens in a new tab)"
                >SixtyFour</a
            >. Flip on space mode at the top for a starfield powered by
            <Link href="https://www.npmjs.com/package/@russellio/vue-background-stars" :class="linkClass">@russellio/vue-background-stars</Link>, an
            open source package for Vue 3 created by me.
        </p>
        <p class="text-center">&copy; Copyright {{ currentYear() }}</p>
    </footer>
</template>
