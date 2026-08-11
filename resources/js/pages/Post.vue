<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DOMPurify from 'dompurify';
import { computed } from 'vue';

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

const props = defineProps<{ post: PostProps }>();

const sanitizedBody = computed(() => (props.post.body ? DOMPurify.sanitize(props.post.body) : ''));
</script>

<template>
    <Head :title="post.title" />

    <article class="mx-auto min-h-screen max-w-screen-md px-6 py-16 font-sans md:px-12 md:py-24">
        <Link href="/" class="group inline-flex items-center text-sm font-semibold text-teal-300 transition-colors hover:text-teal-200">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="mr-1 h-4 w-4 transition-transform group-hover:-translate-x-1 motion-reduce:transition-none"
                aria-hidden="true"
            >
                <path
                    fill-rule="evenodd"
                    d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                    clip-rule="evenodd"
                />
            </svg>
            Back Home
        </Link>

        <header class="mt-8 mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-200 sm:text-4xl">{{ post.title }}</h1>
            <p v-if="post.publishedAt" class="mt-3 text-sm font-semibold tracking-wide text-slate-500 uppercase">{{ post.publishedAt }}</p>
            <p v-if="post.excerpt" class="mt-4 text-lg leading-relaxed text-slate-400">{{ post.excerpt }}</p>
        </header>

        <img
            v-if="post.image"
            :src="post.image.src"
            :alt="post.image.alt"
            class="mb-10 aspect-video w-full rounded-lg border border-slate-800 object-cover"
        />

        <div v-if="sanitizedBody" class="post-body" v-html="sanitizedBody" />
    </article>
</template>

<style scoped>
@reference "@/css/app.css";

.post-body :deep(h2) {
    @apply mt-8 mb-3 text-xl font-semibold text-slate-200;
}

.post-body :deep(p) {
    @apply mb-4 leading-relaxed text-slate-400;
}

.post-body :deep(a) {
    @apply font-medium text-teal-300 hover:underline;
}

.post-body :deep(ul) {
    @apply mb-4 list-disc space-y-1 pl-6 text-slate-400;
}

.post-body :deep(strong) {
    @apply font-semibold text-slate-200;
}

.post-body :deep(code) {
    @apply rounded bg-slate-800 px-1.5 py-0.5 text-sm text-teal-200;
}

.post-body :deep(pre) {
    @apply mb-4 overflow-x-auto rounded-lg border border-slate-800 bg-slate-900/80 p-4 text-sm text-slate-300;
}

.post-body :deep(pre code) {
    @apply bg-transparent p-0 text-slate-300;
}
</style>
