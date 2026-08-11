<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <!-- Mobile: full-bleed sticky bar. Desktop (lg): plain right-aligned display heading. -->
    <h2
        class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 font-sixtyfour text-sm font-bold tracking-widest text-slate-200 uppercase backdrop-blur md:-mx-12 md:px-12 lg:static lg:my-0.5 lg:ms-auto lg:me-0 lg:w-full lg:rounded-tl-xl lg:rounded-tr-xl lg:border-e lg:border-t lg:border-brand-red lg:bg-white/10 lg:ps-0 lg:pe-4 lg:pt-1 lg:pb-0.5 lg:text-right lg:text-xl lg:font-normal lg:text-slate-300 lg:backdrop-blur-none"
    >
        {{ title }}
    </h2>
</template>
