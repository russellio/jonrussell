<script setup lang="ts">
import ProjectModal from '@/js/components/modals/ProjectModal.vue';
import { useModal } from '@/js/composables/useModal';
import projectData from '@/js/data/projects.json';
import { computed, ref } from 'vue';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faAward } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faAward);

const { isOpen, openModal } = useModal();

const projects = ref(projectData);
const selectedProject = ref<any>(null);

const isModalOpen = computed(() => isOpen('project-modal'));

const openProjectModal = (project: any) => {
    selectedProject.value = project;
    openModal('project-modal');
};
</script>

<template>
    <section id="projects">
        <!-- Page Header -->
        <div class="mt-10 mb-16 text-center">
            <h2>Projects</h2>
            <p class="text-2xl text-terminal-black-500">
                A collection of projects I've worked on.
            </p>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-12 w-full lg:w-11/12 lg:mx-auto">
            <div v-for="(project, index) in projects" :key="index" class="card">
                <a aria-label="View project details" class="cursor-pointer">
                    <div class="group project" @click="openProjectModal(project)">
                        <img
                            :src="`/images/projects/${project.bgImage}`"
                            alt="Project Image"
                            :title="`Project: ${project.title}`"
                            class="project-image"
                            :style="`object-position: ${project.bgPositionX} ${project.bgPositionY}`"
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
                            <p>{{ project.byline }}</p>

                            <!-- Technologies -->
                            <div v-if="project.technologies" class="w-full mt-2 flex flex-wrap gap-1 justify-center absolute bottom-4 left-0">
                                <div v-for="(tech, index) in project.technologies" :key="index" class="pill">
                                    <span class="mx-3 my-0.5">{{ tech }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Single Project Modal -->
        <ProjectModal
            v-if="isModalOpen && selectedProject"
            :project="selectedProject"
        />
    </section>
</template>


<style scoped>
@reference "@/css/app.css";

.project {
    @apply relative z-0 h-[290px] lg:h-[260px] overflow-hidden rounded-lg border border-terminal-black-200 align-bottom bg-terminal-black;
}

.project-image {
    @apply absolute opacity-80 transition-opacity duration-300 group-hover:opacity-100;
    @apply z-10 object-none blur-[1px] lg:blur-none;
    object-position: -30px -130px;
    @apply transition-[blur] duration-300 ease-in-out group-hover:blur-[2px];
}

.project-info-wrapper {
    @apply absolute bottom-0 z-50 w-full border-t-1 border-terminal-black-300 bg-terminal-black px-4 pt-2 text-white;
    @apply bg-gradient-to-t from-black via-terminal-black to-transparent;
    @apply opacity-100 lg:opacity-0 lg:transition-opacity lg:duration-300 lg:ease-in-out lg:group-hover:opacity-100;
    @apply h-[9.5rem];
}

.project-title-backdrop {
    @apply absolute z-20 m-1 w-[98%] rounded-md p-0.5 lg:mt-15;
    @apply border border-s-0 border-t-0 border-terminal-black-700;
    @apply lg:transition-all lg:duration-300 lg:ease-in-out lg:group-hover:mt-5;
    @apply sm:max-lg:mt-2;
}

h1 {
    @apply text-2xl lg:text-3xl font-extrabold text-secondary;
    @apply rounded-md border border-white p-1 ps-8 backdrop-blur-sm;
    @apply bg-white/80 backdrop-filter lg:transition-[bg-white] lg:duration-300 lg:ease-in lg:group-hover:bg-white/45;
    @apply sm:max-lg:bg-white/45;
}

div.awards {
    @apply absolute mt-2 grid grid-cols-1 justify-items-center lg:justify-items-end lg:pe-2 opacity-90;
}

div.awards .pill {
    @apply w-max border border-white bg-dark-blue font-bold text-white;
}

.pill {
    @apply inline-flex w-max items-center border border-terminal-black-300 select-none;
    @apply rounded-full p-0 lg:p-0.5 text-xs shadow-sm;
}

.card {

}
</style>
