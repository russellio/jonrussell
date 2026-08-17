<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import type { Post } from '@/js/types/index';
import { computed } from 'vue';

defineProps<{ post: Post }>();

const { isOpen, closeModal } = useModal();
const open = computed({
    get: () => isOpen('post-modal'),
    set: (value: boolean) => {
        if (!value) closeModal('post-modal');
    },
});
</script>

<template>
    <UModal
        v-model:open="open"
        :ui="{
            content: 'max-w-3xl',
            header: 'items-start shrink-0 bg-slate-900/60',
            body: 'bg-slate-900/30',
            footer: 'justify-end shrink-0 bg-slate-900/60',
        }"
    >
        <template #title>
            <span class="block font-space-mono text-[0.65rem] tracking-[0.2em] text-slate-500 uppercase">Post</span>
            <span class="block text-lg leading-tight font-bold text-slate-100 sm:text-xl">{{ post.title }}</span>
        </template>

        <template v-if="post.publishedAt" #description>
            {{ post.publishedAt }}
        </template>

        <template #body>
            <img
                v-if="post.image?.src"
                :src="post.image.src"
                :alt="post.image.alt || post.title"
                class="mb-6 aspect-video w-full rounded-lg border border-white/10 object-cover"
            />

            <p v-if="post.excerpt" class="mb-4 text-sm leading-relaxed text-slate-400">{{ post.excerpt }}</p>

            <div
                v-if="post.body"
                class="space-y-4 text-sm leading-relaxed text-slate-300 [&_a]:text-primary [&_a]:underline [&_code]:rounded [&_code]:bg-slate-800 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:text-blue-200 [&_h2]:mt-6 [&_h2]:mb-2 [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:text-slate-100 [&_li]:ms-5 [&_li]:list-disc [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:border [&_pre]:border-white/10 [&_pre]:bg-slate-950/60 [&_pre]:p-4 [&_pre]:text-slate-300 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_strong]:font-semibold [&_strong]:text-slate-100 [&_ul]:space-y-1"
                v-html="post.body"
            ></div>
        </template>

        <template #footer="{ close }">
            <UButton color="neutral" variant="outline" label="Close" @click="close" />
        </template>
    </UModal>
</template>
