<script setup lang="ts">
import ProjectCard from '@/js/components/ProjectCard.vue';
import ProjectModal from '@/js/components/modals/ProjectModal.vue';
import { useModal } from '@/js/composables/useModal';
import { get } from '@/js/lib/api';
import type { ApiResponse, Project } from '@/js/types/index';
import { computed, onMounted, ref } from 'vue';

const { isOpen, openModal } = useModal();

const projects = ref<Project[]>([]);
const selectedProject = ref<Project | null>(null);
const isLoading = ref(false);
const error = ref<string | null>(null);

const isModalOpen = computed(() => isOpen('project-modal'));

const onSelect = (project: Project) => {
    selectedProject.value = project;
    openModal('project-modal');
};

const fetchProjects = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<Project[]>>('/api/projects');
        projects.value = data;
    } catch (err) {
        console.error('Error fetching projects:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load projects';
        projects.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchProjects();
});
</script>

<template>
    <section id="projects" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Selected projects">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Projects</h2>
        </div>

        <div v-if="isLoading" class="py-8 text-sm text-slate-500">Loading projects…</div>

        <div v-else-if="error" class="py-8 text-sm text-slate-500">
            <p>{{ error }}</p>
            <button
                type="button"
                class="mt-3 rounded border border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-300 transition-colors hover:border-teal-300/50 hover:text-teal-300 motion-reduce:transition-none"
                @click="fetchProjects"
            >
                Retry
            </button>
        </div>

        <div v-else>
            <ul class="group/list">
                <ProjectCard v-for="project in projects" :key="project.id" :project="project" @select="onSelect" />
            </ul>
        </div>

        <ProjectModal v-if="isModalOpen && selectedProject" :project="selectedProject" />
    </section>
</template>
