<script setup lang="ts">
import ProjectCard from '@/js/components/ProjectCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import SectionState from '@/js/components/SectionState.vue';
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
    <section id="projects" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Selected projects">
        <SectionHeading title="Projects" />
        <SectionPanel>
            <SectionState :loading="isLoading" :error="error" @retry="fetchProjects">
                <template #loading>
                    <div class="space-y-6 py-8">
                        <div v-for="n in 3" :key="n" class="grid gap-4 sm:grid-cols-8 sm:gap-8">
                            <div class="sm:col-span-6">
                                <USkeleton class="h-5 w-2/3" />
                                <USkeleton class="mt-2 h-4 w-full" />
                            </div>
                            <USkeleton class="aspect-video sm:col-span-2" />
                        </div>
                    </div>
                </template>

                <div class="mx-4 mt-4">
                    <ul class="group/list">
                        <ProjectCard v-for="project in projects" :key="project.id" :project="project" @select="onSelect" />
                    </ul>
                </div>
            </SectionState>
        </SectionPanel>

        <ProjectModal v-if="isModalOpen && selectedProject" :project="selectedProject" />
    </section>
</template>
