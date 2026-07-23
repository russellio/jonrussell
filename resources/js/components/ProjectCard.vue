<script setup lang="ts">
import type { Project } from '@/js/types';

defineProps<{
    project: Project;
}>();
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
                <h3>
                    <a
                        class="group/link inline-flex items-baseline text-base leading-tight font-medium text-slate-200"
                        :href="project.url"
                        target="_blank"
                        rel="noreferrer noopener"
                        :aria-label="`${project.title} (opens in a new tab)`"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span>
                            {{ project.title }}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="ml-1 inline-block h-4 w-4 shrink-0 translate-y-px transition-transform group-hover/link:translate-x-1 group-hover/link:-translate-y-1 group-focus-visible/link:translate-x-1 group-focus-visible/link:-translate-y-1 motion-reduce:transition-none"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                    </a>
                </h3>
                <p class="mt-2 text-sm leading-normal">{{ project.description }}</p>
                <ul v-if="project.tech.length" class="mt-2 flex flex-wrap" aria-label="Technologies used">
                    <li v-for="tech in project.tech" :key="tech" class="mt-2 mr-1.5">
                        <div class="flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs leading-5 font-medium text-teal-300">
                            {{ tech }}
                        </div>
                    </li>
                </ul>
            </div>
            <img
                :src="project.image"
                :alt="project.title"
                loading="lazy"
                class="aspect-video rounded border-2 border-slate-200/10 object-cover transition group-hover:border-slate-200/30 sm:order-1 sm:col-span-2 sm:translate-y-1"
            />
        </div>
    </li>
</template>
