<script setup lang="ts">
const props = defineProps<{
    title: string;
    shortTitle?: string;
}>();

const hasShortTitle = () => Boolean(props.shortTitle && props.shortTitle.length > 0);

const titleClass =
    'sticky top-0 z-20 my-1 w-full rounded-tl-xl rounded-tr-xl border-s border-e border-t border-brand-red border-s-white/15 bg-slate-700/90 md:bg-slate-700/75 py-2 pe-10 text-right font-sixtyfour text-xl tracking-widest text-slate-200 backdrop-blur uppercase lg:static lg:my-0.5 lg:ms-auto lg:me-0 lg:pb-0.5 lg:tracking-tighter lg:text-slate-300 ';
</script>

<template>
    <!-- Mobile: full-bleed sticky bar. Desktop (lg): plain right-aligned display heading. -->
    <h2 :class="titleClass">
        <span v-if="hasShortTitle()" class="lg:hidden">{{ shortTitle }}</span>
        <span :class="hasShortTitle() ? 'hidden lg:block' : ''">{{ title }}</span>
    </h2>
</template>
