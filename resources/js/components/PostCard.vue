<script setup lang="ts">
import type { Post } from '@/js/types/index';

const props = defineProps<{
    post: Post;
}>();

const emit = defineEmits<{
    select: [post: Post];
}>();

const onSelect = (): void => {
    if (props.post.hasBody) emit('select', props.post);
};
</script>

<template>
    <li class="mb-12">
        <div
            class="group relative grid grid-cols-8 gap-4 transition-all sm:items-center sm:gap-8 md:gap-4 lg:group-hover/list:opacity-50 lg:hover:opacity-100!"
            :class="post.hasBody ? 'cursor-pointer' : ''"
            @click="onSelect"
        >
            <div
                class="absolute -inset-6 -inset-s-8 -inset-e-2 z-0 hidden rounded-md border-white/0 transition motion-reduce:transition-none lg:block lg:group-hover:border lg:group-hover:border-white/25 lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <img
                v-if="post.image?.src"
                :src="post.image.src"
                :alt="post.image.alt || post.title"
                loading="lazy"
                class="z-10 col-span-2 aspect-video rounded border-2 border-slate-200/10 object-cover transition group-hover:border-slate-200/30 sm:col-span-2"
            />
            <div class="z-10" :class="post.image?.src ? 'col-span-6' : 'col-span-8'">
                <p v-if="post.year" class="-mt-1 text-sm leading-6 font-semibold">{{ post.year }}</p>
                <h3 class="">
                    <button
                        v-if="post.hasBody"
                        type="button"
                        class="group/link relative inline-flex items-baseline text-base leading-tight font-medium text-slate-200 transition-colors hover:text-primary focus-visible:text-primary"
                        :aria-label="`Read ${post.title}`"
                        @click="onSelect"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span class="text-xl font-semibold">{{ post.title }}</span>
                    </button>
                    <a
                        v-else-if="post.externalUrl"
                        class="group/link relative inline-flex items-baseline text-base leading-tight font-medium text-slate-200"
                        :href="post.externalUrl"
                        target="_blank"
                        rel="noreferrer noopener"
                        :aria-label="`${post.title} (opens in a new tab)`"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span>
                            {{ post.title }}
                            <UIcon name="i-lucide-external-link" class="h-3.5 w-3.5 shrink-0 text-primary" />
                        </span>
                    </a>
                    <span v-else class="inline-flex items-baseline text-base leading-tight font-medium text-slate-200">{{ post.title }}</span>
                </h3>
            </div>
        </div>
    </li>
</template>
