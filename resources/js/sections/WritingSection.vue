<script setup lang="ts">
import PostCard from '@/js/components/PostCard.vue';
import { posts } from '@/js/data/posts';
</script>

<template>
    <section id="writing" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Blog posts">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Writing</h2>
        </div>
        <div>
            <ul class="group/list">
                <PostCard v-for="post in posts" :key="post.id" :post="post" />
            </ul>
        </div>
    </section>
</template>
