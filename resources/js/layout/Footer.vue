<script setup lang="ts">
const linkClass = 'font-medium text-slate-300 underline underline-offset-2 hover:text-primary-blue-400 focus-visible:text-primary-blue-400';

const credits = [
    { name: 'Vue 3', url: 'https://vuejs.org/' },
    { name: 'Nuxt', url: 'https://ui.nuxt.com/' },
    { name: 'Laravel 13', url: 'https://laravel.com/' },
    { name: 'Inertia', url: 'https://inertiajs.com/' },
    { name: 'Tailwind CSS', url: 'https://tailwindcss.com/' },
];

const fonts = [
    { name: 'Noto Sans', url: 'https://github.com/notofonts/latin-greek-cyrillic' },
    { name: 'Space Mono', url: 'https://github.com/googlefonts/spacemono' },
    { name: 'SixtyFour', url: 'https://jenskutilek.github.io/homecomputer-fonts/documentation/demo-sixtyfour.html' },
];

const currentYear = () => {
    return new Date().getFullYear();
};

/** Joins a list with commas and a final ", and" — the separator that follows item `index`. */
function separatorAfter(index: number, length: number): string {
    if (index === length - 1) return '';
    return index === length - 2 ? ', and ' : ', ';
}
</script>

<template>
    <footer class="w-full pb-16 text-xs text-slate-400 sm:pb-0">
        <p class="mb-2 text-justify">
            Site designed and built by Jon Russell with
            <template v-for="(credit, index) in credits" :key="credit.url"
                ><a :href="credit.url" :class="linkClass" target="_blank" rel="noreferrer noopener"
                    >{{ credit.name }}<span class="sr-only"> (opens in a new tab)</span></a
                >{{ separatorAfter(index, credits.length) }}</template
            >, set in
            <template v-for="(font, index) in fonts" :key="font.url"
                ><a :href="font.url" :class="linkClass" target="_blank" rel="noreferrer noopener"
                    >{{ font.name }}<span class="sr-only"> (opens in a new tab)</span></a
                >{{ separatorAfter(index, fonts.length) }}</template
            >. Flip on space mode with the toggle in the corner for a starfield powered by
            <a href="https://www.npmjs.com/package/@russellio/vue-background-stars" :class="linkClass" target="_blank" rel="noreferrer noopener"
                >@russellio/vue-background-stars<span class="sr-only"> (opens in a new tab)</span></a
            >, an open source package for Vue 3 I created.
        </p>
        <p class="text-center">&copy; Copyright {{ currentYear() }}</p>
    </footer>
</template>
