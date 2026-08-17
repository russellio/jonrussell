<script setup lang="ts">
import PostCard from '@/js/components/PostCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import PostModal from '@/js/components/modals/PostModal.vue';
import { useModal } from '@/js/composables/useModal';
import type { Post } from '@/js/types/index';
import { computed, ref } from 'vue';

defineProps<{
    posts: Post[];
}>();

const { isOpen, openModal } = useModal();

const selectedPost = ref<Post | null>(null);

const isModalOpen = computed(() => isOpen('post-modal'));

const onSelect = (post: Post) => {
    selectedPost.value = post;
    openModal('post-modal');
};
</script>

<template>
    <section id="posts" class="scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Posts">
        <SectionHeading title="Posts" />
        <SectionPanel class="ps-8 pe-2 pt-6 pb-0">
            <div v-if="posts.length" class="space-y-6">
                <ul class="group/list">
                    <PostCard v-for="post in posts" :key="post.id" :post="post" @select="onSelect" />
                </ul>
            </div>
        </SectionPanel>

        <PostModal v-if="isModalOpen && selectedPost" :post="selectedPost" />
    </section>
</template>
