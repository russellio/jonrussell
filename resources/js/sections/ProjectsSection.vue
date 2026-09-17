<script setup lang="ts">
import ProjectCard from '@/js/components/ProjectCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import { useModal } from '@/js/composables/useModal';
import { useProjectsStore } from '@/js/stores/projectsStore';
import type { Project } from '@/js/types';

defineProps<{
    projects: Project[];
}>();

const { openModal } = useModal();
const projectsStore = useProjectsStore();

const onSelect = (project: Project) => {
    projectsStore.selectProject(project.id);
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
    </section>
</template>
