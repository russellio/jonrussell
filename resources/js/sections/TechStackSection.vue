<script setup lang="ts">
import { get } from '@/js/lib/api';
import type { ApiResponse, TechStackItem } from '@/js/types/index';
import { onMounted, ref } from 'vue';

import { faCss3, faHtml5, faJs, faLaravel, faPhp, faReact, faVuejs } from '@fortawesome/free-brands-svg-icons';
import { faCode, faDatabase, faProjectDiagram, faSitemap, faVial } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import type { Component } from 'vue';
import { MySqlIcon, PythonIcon, ReactIcon, TypeScriptIcon } from 'vue3-simple-icons';

const simpleIcons = [{ component: TypeScriptIcon }, { component: ReactIcon }, { component: MySqlIcon }, { component: PythonIcon }];

const faIcons = [
    { group: 'fas', name: faDatabase },
    { group: 'fas', name: faCode },
    { group: 'fas', name: faVial },
    { group: 'fas', name: faProjectDiagram },
    { group: 'fas', name: faSitemap },
    { group: 'fab', name: faLaravel },
    { group: 'fab', name: faPhp },
    { group: 'fab', name: faVuejs },
    { group: 'fab', name: faReact },
    { group: 'fab', name: faJs },
    { group: 'fab', name: faHtml5 },
    { group: 'fab', name: faCss3 },
];

const getFaIcon = (iconName: string): [string, string] => {
    const icon = faIcons.find((icon) => icon.name.iconName === iconName);
    return icon ? [icon.group, iconName] : ['', ''];
};

const getSimpleIcon = (iconName: string): Component | null => {
    return simpleIcons.find((icon) => icon.component.__name === iconName)?.component ?? null;
};

const techStack = ref<TechStackItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const fetchTechStack = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<TechStackItem[]>>('/api/tech-stack');
        techStack.value = data;
    } catch (err) {
        console.error('Error fetching tech stack:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load tech stack';
        techStack.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchTechStack();
});
</script>

<template>
    <section id="tech-stack" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Tech stack">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Tech Stack</h2>
        </div>

        <div v-if="isLoading" class="py-8 text-sm text-slate-500">Loading tech stack…</div>

        <div v-else-if="error" class="py-8 text-sm text-slate-500">
            <p>{{ error }}</p>
            <button
                type="button"
                class="mt-3 rounded border border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-300 transition-colors hover:border-teal-300/50 hover:text-teal-300 motion-reduce:transition-none"
                @click="fetchTechStack"
            >
                Retry
            </button>
        </div>

        <ul v-else class="space-y-3">
            <li v-for="item in techStack" :key="item.tech" class="flex items-center gap-3">
                <FontAwesomeIcon
                    v-if="item.iconType === 'fa' && item.iconName && getFaIcon(item.iconName)[0]"
                    :icon="getFaIcon(item.iconName)"
                    class="h-4 w-4 shrink-0 text-slate-500"
                />
                <component
                    v-else-if="item.iconType === 'si' && item.iconName && getSimpleIcon(item.iconName)"
                    :is="getSimpleIcon(item.iconName)"
                    class="h-4 w-4 shrink-0 fill-current text-slate-500"
                />
                <span class="text-sm text-slate-300">
                    {{ item.tech }}
                    <span v-if="item.active" class="ml-1 text-xs text-teal-300">(current focus)</span>
                </span>
                <span class="ml-auto text-xs tabular-nums" :class="item.active ? 'text-teal-300' : 'text-slate-500'">{{ item.percent }}%</span>
            </li>
        </ul>
    </section>
</template>
