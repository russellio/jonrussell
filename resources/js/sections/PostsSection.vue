<script setup lang="ts">
import PostCard from '@/js/components/PostCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
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
    <section id="posts" class="scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Posts">
        <SectionHeading title="Posts" />
        <SectionPanel class="ps-4 pe-2 pt-6">
            <SectionState :loading="isLoading" :error="error" @retry="fetchPosts">
                <template #loading>
                    <div class="space-y-6 py-8">
                        <div v-for="n in 3" :key="n" class="sm:col-span-6">
                            <USkeleton class="h-5 w-2/3" />
                            <USkeleton class="mt-2 h-4 w-full" />
                        </div>
                    </div>
                </template>

                <div v-if="posts.length" class="space-y-6">
                    <ul class="group/list">
                        <PostCard v-for="post in posts" :key="post.id" :post="post" />
                    </ul>
                </div>
            </SectionState>
        </SectionPanel>
    </section>
</template>
