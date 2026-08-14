<script setup lang="ts">
import PostCard from '@/js/components/PostCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import type { Post } from '@/js/types/index';

defineProps<{
    posts: Post[];
}>();
</script>

<template>
    <section id="posts" class="scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Posts">
        <SectionHeading title="Posts" />
        <SectionPanel class="pb-0 pe-2 ps-8 pt-6">
            <div v-if="posts.length" class="space-y-6">
                <ul class="group/list">
                    <PostCard v-for="post in posts" :key="post.id" :post="post" />
                </ul>
            </div>
        </SectionPanel>
    </section>
</template>
