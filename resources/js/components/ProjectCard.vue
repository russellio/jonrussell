<script setup lang="ts">
import type { Project } from '@/js/types/index';
import { faAward } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
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
    <li class="mb-12">
        <div
            class="group relative grid gap-4 pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:group-hover/list:opacity-50 lg:hover:!opacity-100"
        >
            <div
                class="absolute -inset-x-4 -inset-y-4 z-0 hidden rounded-md transition motion-reduce:transition-none lg:-inset-x-6 lg:block lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <div class="z-10 sm:order-2 sm:col-span-6">
                <h3 class="leading-snug font-medium text-slate-200">
                    <button
                        type="button"
                        class="group/link relative inline-flex items-baseline text-base leading-tight font-medium text-slate-200 transition-colors hover:text-teal-300 focus-visible:text-teal-300 motion-reduce:transition-none"
                        :aria-label="`View details for ${project.title}`"
                        @click="emit('select', project)"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span>{{ project.title }}</span>
                    </button>
                </h3>
                <p class="mt-2 text-sm leading-normal">{{ project.byline }}</p>
                <ul v-if="project.awards && project.awards.length > 0" class="mt-2 flex flex-wrap gap-1.5" aria-label="Awards">
                    <li
                        v-for="award in project.awards"
                        :key="award"
                        class="inline-flex items-center gap-1 rounded-full border border-teal-300/30 px-2.5 py-0.5 text-[11px] font-medium text-teal-300"
                    >
                        <FontAwesomeIcon :icon="faAward" class="h-3 w-3" />
                        {{ award }}
                    </li>
                </ul>
                <ul v-if="project.highlightedSkills.length" class="mt-2 flex flex-wrap" aria-label="Technologies used">
                    <li v-for="skill in project.highlightedSkills" :key="skill" class="mt-2 mr-1.5">
                        <div class="flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs leading-5 font-medium text-teal-300">
                            {{ skill }}
                        </div>
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
