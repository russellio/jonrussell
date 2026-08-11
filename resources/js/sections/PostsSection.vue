<script setup lang="ts">
import PostCard from '@/js/components/PostCard.vue';
import SectionState from '@/js/components/SectionState.vue';
import { get } from '@/js/lib/api';
import type { ApiResponse, Post } from '@/js/types/index';
import { onMounted, ref } from 'vue';

const posts = ref<Post[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const fetchPosts = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<Post[]>>('/api/posts');
        posts.value = data;
    } catch (err) {
        console.error('Error fetching posts:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load posts';
        posts.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchPosts();
});
</script>

<template>
    <section id="posts" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Blog posts">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Posts</h2>
        </div>

        <SectionState :loading="isLoading" :error="error" @retry="fetchPosts">
            <template #loading>
                <div class="space-y-6 py-8">
                    <div v-for="n in 3" :key="n" class="sm:col-span-6">
                        <USkeleton class="h-5 w-2/3" />
                        <USkeleton class="mt-2 h-4 w-full" />
                    </div>
                </div>
            </template>

            <div v-if="posts.length">
                <ul class="group/list">
                    <PostCard v-for="post in posts" :key="post.id" :post="post" />
                </ul>
            </div>
        </SectionState>
    </section>
</template>
