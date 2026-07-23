<script setup lang="ts">
import { site } from '@/js/data/site';
import Nav from '@/js/layout/Nav.vue';
import Socials from '@/js/layout/Socials.vue';
</script>

<template>
    <header class="lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-slate-200 sm:text-5xl">{{ site.name }}</h1>
            <h2 class="mt-3 text-lg font-medium tracking-tight text-slate-200 sm:text-xl">{{ site.role }}</h2>
            <p class="mt-4 max-w-xs leading-normal">{{ site.tagline }}</p>
            <Nav />
        </div>
        <Socials />
    </header>
</template>
