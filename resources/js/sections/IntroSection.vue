<script setup lang="ts">
import { site } from '@/js/data/site';
</script>

<template>
    <section id="about" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="About me">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">About</h2>
        </div>
        <div>
            <p v-for="(paragraph, index) in site.about" :key="index" :class="{ 'mb-4': index < site.about.length - 1 }">
                {{ paragraph }}
            </p>
        </div>
    </section>
</template>
