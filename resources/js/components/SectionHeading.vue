<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <!-- Mobile: full-bleed sticky bar. Desktop (lg): plain right-aligned display heading. -->
    <h2
        class="sticky top-0 z-20 my-1 w-full rounded-tl-xl rounded-tr-xl border-s border-e border-t border-brand-red border-s-white/25 bg-white/10 py-2 pe-10 text-right font-sixtyfour text-xl tracking-widest text-slate-200 uppercase backdrop-blur lg:static lg:my-0.5 lg:ms-auto lg:me-0 lg:pb-0.5 lg:tracking-tighter lg:text-slate-300 lg:backdrop-blur-none"
    >
        {{ title }}
    </h2>
</template>
