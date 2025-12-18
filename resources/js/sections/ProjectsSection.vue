<script setup lang="ts">
import ProjectModal from '@/js/components/modals/ProjectModal.vue';
import { useModal } from '@/js/composables/useModal';
import type { Project } from '@/js/types/index';
import { computed, onMounted, ref } from 'vue';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faAward } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faAward);

interface ProjectsResponse {
    success: boolean;
    data: Project[];
}

const { isOpen, openModal } = useModal();

const projects = ref<Project[]>([]);
const selectedProject = ref<Project | null>(null);
const isLoadingProjects = ref(false);
const projectsError = ref<string | null>(null);

const isModalOpen = computed(() => isOpen('project-modal'));

const openProjectModal = (project: Project) => {
    selectedProject.value = project;
    openModal('project-modal');
};

const fetchProjects = async () => {
    isLoadingProjects.value = true;
    projectsError.value = null;

    try {
        const response = await fetch('/api/projects', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch projects');
        }

        const data: ProjectsResponse = await response.json();

        if (data.success && data.data) {
            projects.value = data.data;
        } else {
            throw new Error('Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching projects:', error);
        projectsError.value = error instanceof Error ? error.message : 'Failed to load projects';
    } finally {
        isLoadingProjects.value = false;
    }
};

onMounted(() => {
    fetchProjects();
});
</script>

<template>
    <section>
        <!-- Page Header -->
        <div class="mt-10 mb-16 text-center">
            <h2>Projects</h2>
            <p class="text-2xl text-terminal-black-500">A collection of projects I've worked on.</p>
        </div>

        <!-- Projects Grid -->
        <div v-if="isLoadingProjects" class="py-4 text-center">
            <p class="text-gray-500">Loading projects...</p>
        </div>
        <div v-else-if="projectsError" class="py-4 text-center">
            <p class="text-red-500">{{ projectsError }}</p>
            <button @click="fetchProjects" class="hover:bg-primary-dark mt-2 rounded bg-primary px-4 py-2 text-white">Retry</button>
        </div>
        <div v-else class="grid w-full grid-cols-1 gap-4 md:grid-cols-2 xl:mx-auto xl:w-11/12 xl:gap-12">
            <div v-for="(project, index) in projects" :key="index" class="card">
                <a aria-label="View project details" class="cursor-pointer">
                    <div class="group project" @click="openProjectModal(project)">
                        <img
                            v-if="project.bgImage"
                            :src="`/images/projects/${project.bgImage}`"
                            alt="Project Image"
                            :title="`Project: ${project.title}`"
                            class="project-image"
                            :style="project.bgPositionX && project.bgPositionY ? `object-position: ${project.bgPositionX} ${project.bgPositionY}` : ''"
                        />

                        <div class="project-title-backdrop">
                            <h1>{{ project.title }}</h1>

                            <div v-if="project.awards" class="awards">
                                <div v-for="(award, index) in project.awards" :key="index" class="pill">
                                    <span class="mx-2.5 my-1 leading-none">
                                        <FontAwesomeIcon :icon="faAward" />
                                        {{ award }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="project-info-wrapper">
                            <p class="max-xl:leading-4.5">{{ project.byline }}</p>

                            <!-- Highlighted Skills -->
                            <div v-if="project.highlightedSkills && project.highlightedSkills.length > 0" class="absolute bottom-4 left-0 flex w-full flex-wrap justify-center gap-1">
                                <div v-for="(skill, index) in project.highlightedSkills" :key="index" class="pill">
                                    <span class="mx-3 my-0.5">{{ skill }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Single Project Modal -->
        <ProjectModal v-if="isModalOpen && selectedProject" :project="selectedProject" />
    </section>
</template>

<style scoped>
@reference "@/css/app.css";

.project {
    @apply relative z-0 h-[290px] overflow-hidden rounded-lg;
    @apply border-x border-y-4 border-terminal-black-600 bg-terminal-black align-bottom lg:h-[260px];
}

.project-image {
    @apply absolute opacity-80 transition-opacity duration-300 group-hover:opacity-100;
    @apply z-10 border-x-3 border-t border-white object-none blur-[1px] lg:blur-none;
    @apply transition-[blur] duration-300 ease-in-out group-hover:blur-[2px];
    object-position: -30px -130px;
}

.project-info-wrapper {
    /*    @apply absolute bottom-0 z-50 w-full border-t border-terminal-black-300 bg-terminal-black px-2 pt-2 text-white lg:px-4;
    @apply bg-linear-to-t from-transparent via-terminal-black to-terminal-black;
    @apply opacity-100 lg:transition-opacity lg:duration-300 lg:ease-in-out lg:group-hover:opacity-100;
    */
    @apply absolute bottom-0 z-50 w-full border-t border-terminal-black-300 bg-terminal-black px-2 pt-2 text-white xl:px-4;
    @apply bg-linear-to-t from-black via-terminal-black to-transparent;
    @apply opacity-100 xl:opacity-0 xl:transition-opacity xl:duration-300 xl:ease-in-out xl:group-hover:opacity-100;
    @apply no-hover:h-34 h-22 transition-[height] group-hover:h-34;
}

.project-info-wrapper p {
    @apply opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100;
}

.project-title-backdrop {
    @apply absolute z-20 m-1 w-[98%] rounded-md p-0.5;
    @apply border border-s-0 border-t-0 border-terminal-black/70;
    @apply no-hover:mt-1 mt-10 md:mt-6 xl:mt-15;
    @apply transition-all duration-300 ease-in-out group-hover:mt-2;
}

h1 {
    @apply text-2xl font-extrabold text-secondary lg:text-3xl;
    @apply rounded-md border border-white p-1 ps-8 backdrop-blur-sm;
    @apply bg-white/90 backdrop-filter lg:transition-[bg-white] lg:duration-300 lg:ease-in lg:group-hover:bg-white;
}

h1 span {
    @apply text-secondary!;
}

div.awards {
    @apply absolute mt-2 grid grid-cols-1 justify-items-center opacity-90 lg:justify-items-end lg:pe-2;
}

div.awards .pill {
    @apply w-max border border-white bg-dark-blue font-bold text-white;
}

.pill {
    @apply inline-flex w-max items-center border border-terminal-black/30 select-none;
    @apply rounded-full p-0 text-xs shadow-sm lg:p-0.5;
}

.project-info-wrapper .pill {
    @apply border border-white/50 bg-terminal-black-700/80 text-white;
}
</style>
