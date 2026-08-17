<script setup lang="ts">
import ProjectCard from '@/js/components/ProjectCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import ProjectModal from '@/js/components/modals/ProjectModal.vue';
import { useModal } from '@/js/composables/useModal';
import type { Project } from '@/js/types';
import { computed, ref } from 'vue';

defineProps<{
    projects: Project[];
}>();

const { isOpen, openModal } = useModal();

const selectedProject = ref<Project | null>(null);

const isModalOpen = computed(() => isOpen('project-modal'));

const onSelect = (project: Project) => {
    selectedProject.value = project;
    openModal('project-modal');
};
</script>

<template>
    <section id="projects" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Selected projects">
        <SectionHeading title="Projects" />
        <SectionPanel>
            <div class="mx-4 mt-4">
                <ul class="group/list">
                    <ProjectCard v-for="project in projects" :key="project.id" :project="project" @select="onSelect" />
                </ul>
            </div>
        </SectionPanel>

        <ProjectModal v-if="isModalOpen && selectedProject" :project="selectedProject" />
    </section>
</template>
