<script setup lang="ts">
interface PostImage {
    src: string;
    alt: string;
}

interface PostProps {
    title: string;
    excerpt: string | null;
    body: string | null;
    publishedAt: string | null;
    image: PostImage | null;
}

defineProps<{ post: PostProps }>();
</script>

<template>
    <div class="mx-auto min-h-screen max-w-screen-md px-6 py-12 font-sans md:px-12 md:py-16">
        <article>
            <header class="mb-8">
                <h1 class="text-3xl font-bold">{{ post.title }}</h1>
                <p v-if="post.publishedAt" class="mt-2 text-sm opacity-70">{{ post.publishedAt }}</p>
                <p v-if="post.excerpt" class="mt-4 text-lg opacity-90">{{ post.excerpt }}</p>
            </header>
            <img v-if="post.image" :src="post.image.src" :alt="post.image.alt" class="mb-8 w-full rounded" />
            <div v-if="post.body" class="prose max-w-none" v-html="post.body" />
        </article>
    </div>
</template>
