import type { Project } from '@/js/types';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useProjectsStore = defineStore('projects', () => {
    const projects = ref<Project[]>([]);
    const selectedSlug = ref<string | null>(null);

    const selectedProject = computed(() => projects.value.find((p) => p.id === selectedSlug.value) ?? null);

    function setProjects(list: Project[]): void {
        projects.value = list;
    }

    function selectProject(slug: string): boolean {
        const found = projects.value.some((p) => p.id === slug);
        if (found) selectedSlug.value = slug;
        return found;
    }

    return { projects, selectedSlug, selectedProject, setProjects, selectProject };
});
