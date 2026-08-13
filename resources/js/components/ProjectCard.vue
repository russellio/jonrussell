<script setup lang="ts">
import type { Project } from '@/js/types/index';
import { computed } from 'vue';

const props = defineProps<{
    project: Project;
}>();

const emit = defineEmits<{
    select: [project: Project];
}>();

const thumbSrc = computed(() => {
    const src = props.project.primaryImage?.src ?? props.project.bgImage;
    return src ? `/images/projects/${src}` : null;
});
</script>

<template>
    <li class="mb-10">
        <div
            class="group relative grid gap-4 pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:group-hover/list:opacity-50 lg:hover:opacity-100!"
        >
            <div
                class="absolute -inset-6 -inset-s-8 z-0 hidden rounded-md border-white/0 transition motion-reduce:transition-none lg:block lg:group-hover:border lg:group-hover:border-white/25 lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <div class="z-10 sm:order-2 sm:col-span-6">
                <h3 class="leading-snug font-medium text-slate-200">
                    <button
                        type="button"
                        class="group/link relative inline-flex items-baseline text-base leading-tight font-medium text-slate-200 transition-colors hover:text-blue-300 focus-visible:text-blue-300 motion-reduce:transition-none"
                        :aria-label="`View details for ${project.title}`"
                        @click="emit('select', project)"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span>{{ project.title }}</span>
                    </button>
                </h3>
                <p class="mt-2 text-sm leading-normal">{{ project.byline }}</p>
                <ul v-if="project.awards && project.awards.length > 0" class="mt-2 flex flex-wrap gap-1.5" aria-label="Awards">
                    <li v-for="award in project.awards" :key="award">
                        <UBadge color="secondary" variant="outline" size="sm" class="rounded-full bg-primary/30 px-2">
                            <UIcon name="i-lucide-award" class="h-4 w-4 text-gold" />
                            {{ award }}
                        </UBadge>
                    </li>
                </ul>
                <ul v-if="project.highlightedSkills.length" class="mt-2 flex flex-wrap" aria-label="Technologies used">
                    <li v-for="skill in project.highlightedSkills" :key="skill" class="mt-2 mr-1.5">
                        <UBadge color="secondary" variant="soft" size="md" class="rounded-sm bg-white/10">{{ skill }}</UBadge>
                    </li>
                </ul>
            </div>
            <img
                v-if="thumbSrc"
                :src="thumbSrc"
                :alt="project.title"
                loading="lazy"
                class="aspect-video rounded border-2 border-slate-200/10 object-cover transition group-hover:border-slate-200/30 sm:order-1 sm:col-span-2 sm:translate-y-1"
            />
        </div>
    </li>
</template>
