<script setup lang="ts">
import ImageModal from '@/js/components/modals/ImageModal.vue';
import { useModal } from '@/js/composables/useModal';
import type { Image, Project } from '@/js/types/index';
import DOMPurify from 'dompurify';
import type { Component } from 'vue';
import { computed, ref } from 'vue';

import { faCss3, faHtml5, faJs, faLaravel, faPhp, faReact, faVuejs } from '@fortawesome/free-brands-svg-icons';
import { faArrowUpRightFromSquare, faCode, faDatabase, faProjectDiagram, faSitemap, faVial } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { MySqlIcon, PythonIcon, ReactIcon, TypeScriptIcon } from 'vue3-simple-icons';

const sanitizeHtml = (html: string) => DOMPurify.sanitize(html);

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

const props = defineProps<{ project: Project }>();

const { isOpen, closeModal } = useModal();
const open = computed({
    get: () => isOpen('project-modal'),
    set: (value: boolean) => {
        if (!value) closeModal('project-modal');
    },
});

const imageModalOpen = ref(false);
const selectedImage = ref<Image | null>(null);
const showImage = (image: Image) => {
    selectedImage.value = image;
    imageModalOpen.value = true;
};

const projectHasProp = <K extends keyof Project>(project: Project, property: K): boolean => {
    const value = project[property];

    if (Array.isArray(value)) {
        return value.length > 0;
    }

    if (typeof value === 'string') {
        return value.length > 0;
    }

    if (value && typeof value === 'object') {
        return Object.keys(value).length > 0;
    }

    return Boolean(value);
};

const hasModalLeft = computed(() => {
    return projectHasProp(props.project, 'primaryImage');
});

const hasModalRight = computed(() => {
    return (props.project.technologies && props.project.technologies.length > 0) || (props.project.tools && props.project.tools.length > 0);
});

const companyLogoSrc = computed(() => {
    return props.project.company?.logo?.src ? `/images/logos/${props.project.company.logo.src}` : null;
});
const companyLogoText = computed(() => {
    return props.project.company?.logo?.displayName ? props.project.company.name : null;
});
</script>

<template>
    <UModal v-model:open="open" :ui="{ content: 'max-w-7xl' }">
        <template #header>
            project: <span class="inline-block text-slate-100! normal-case md:block">{{ project.title }}</span>
        </template>
        <template #body>
            <div class="grid w-full grid-cols-1 lg:grid-cols-[minmax(18%,120px)_auto_18%]">
                <div v-if="hasModalLeft" class="modal-left mb-10" :class="{ '': !projectHasProp(project, 'images') }">
                    <div v-if="projectHasProp(project, 'primaryImage')" class="mb-6 cursor-pointer" @click="showImage(project.primaryImage!)">
                        <img
                            :src="`/images/projects/${project.primaryImage?.src}`"
                            :title="project.primaryImage?.title"
                            :alt="project.primaryImage?.alt ?? project.primaryImage?.title"
                            class="mx-auto w-4/5 rounded-md object-fill lg:w-full"
                        />
                    </div>

                    <div v-if="projectHasProp(project, 'images')" class="thumbnails">
                        <div v-for="image in project.images" :key="image.src" class="thumbnail" @click="showImage(image)">
                            <img v-if="image?.src" :src="`/images/projects/${image.src}`" :title="image.title" :alt="image.alt" />
                        </div>
                    </div>
                </div>

                <div class="modal-center w-full">
                    <div v-if="projectHasProp(project, 'company')" class="company">
                        <div class="flex w-full flex-col font-sans tracking-widest text-slate-100! md:flex-row">
                            <h3>company:</h3>
                            <div v-if="companyLogoSrc" class="flex grow flex-col justify-center py-2 md:flex-row">
                                <div class="align-end pe-4">
                                    <img
                                        v-if="companyLogoSrc"
                                        :src="companyLogoSrc"
                                        :alt="project.company?.logo.alt || project.company?.name"
                                        class="mx-auto h-10"
                                    />
                                </div>
                                <div v-if="companyLogoText" class="self-center text-xl">{{ companyLogoText }}</div>
                            </div>
                            <span v-else>{{ project.company?.name }}</span>
                        </div>
                    </div>

                    <div v-if="projectHasProp(project, 'keyTakeaways')" class="key-takeaways mx-auto mt-5 mb-4 w-11/12">
                        <h4 class="mt-0 mb-2 font-space-mono text-slate-100!">key takeaways:</h4>
                        <div class="rounded-md border-y border-slate-700 bg-slate-900/60 p-2 py-6 ps-8">
                            <ul class="list-disc space-y-2 border-s border-slate-700 ps-10">
                                <li v-for="takeaway in project.keyTakeaways" :key="takeaway">
                                    {{ takeaway }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="projectHasProp(project, 'description')">
                        <h3>description:</h3>
                        <div v-html="sanitizeHtml(project.description ?? '')" class="description" />
                    </div>

                    <div v-if="projectHasProp(project, 'links')" class="links">
                        <h4 class="mt-0 mb-2 ps-4 font-space-mono text-slate-100!">links:</h4>
                        <ul class="list-disc space-y-2 rounded-md border-t border-b-4 border-slate-700 bg-slate-900/60 p-2 py-3 ps-10">
                            <li v-for="link in project.links" :key="link.url">
                                <a :href="link.url" target="_blank" class="text-slate-300 hover:text-teal-300">{{ link.title }}</a>
                                <FontAwesomeIcon :icon="faArrowUpRightFromSquare" class="ps-2 text-teal-300" size="sm" />
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-if="hasModalRight" class="modal-right">
                    <div v-if="project.technologies && project.technologies.length > 0" class="technologies">
                        <h3>skills:</h3>
                        <ul>
                            <li v-for="tech in project.technologies" :key="tech.name" class="flex items-center gap-2">
                                <FontAwesomeIcon
                                    v-if="tech.iconType === 'fa' && tech.iconName && getFaIcon(tech.iconName)[0]"
                                    :icon="getFaIcon(tech.iconName)"
                                    class="inline-block h-5 w-5"
                                />
                                <component
                                    v-else-if="tech.iconType === 'si' && tech.iconName && getSimpleIcon(tech.iconName)"
                                    :is="getSimpleIcon(tech.iconName)"
                                    class="inline-block h-5 w-5 fill-current"
                                />
                                <span v-else class="list-marker">•</span>
                                {{ tech.name }}
                            </li>
                        </ul>
                    </div>

                    <div v-if="project.tools && project.tools.length > 0" class="tools">
                        <h3>tools:</h3>
                        <ul>
                            <li v-for="tool in project.tools" :key="tool.name" class="flex items-center gap-2">
                                <FontAwesomeIcon
                                    v-if="tool.iconType === 'fa' && tool.iconName && getFaIcon(tool.iconName)[0]"
                                    :icon="getFaIcon(tool.iconName)"
                                    class="inline-block h-5 w-5"
                                />
                                <component
                                    v-else-if="tool.iconType === 'si' && tool.iconName && getSimpleIcon(tool.iconName)"
                                    :is="getSimpleIcon(tool.iconName)"
                                    class="inline-block h-5 w-5 fill-current"
                                />
                                <span v-else class="list-marker">•</span>
                                {{ tool.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <ImageModal v-model:open="imageModalOpen" :image="selectedImage" />
        </template>
        <template #footer="{ close }">
            <UButton color="neutral" variant="outline" label="Close" @click="close" />
        </template>
    </UModal>
</template>

<style scoped>
@reference "@/css/app.css";

h3 {
    @apply mt-3 font-space-mono text-teal-300!;
}

.thumbnails {
    @apply mx-auto mt-1 flex flex-wrap gap-1;
    @apply md:grid md:w-4/5 md:grid-cols-6;
    @apply lg:mt-0 lg:w-full lg:grid-cols-2 lg:gap-2;
}

.thumbnail {
    @apply mx-auto my-1 h-32 w-32 cursor-pointer overflow-hidden border-y border-slate-700;
    @apply rounded-md bg-slate-900/60 md:h-20 md:w-20;
}

.thumbnail img {
    @apply h-full w-full object-cover;
}

.company {
    @apply flex flex-row rounded-md border border-y-4 border-b-0 border-slate-700 bg-slate-900/60 px-4;
}

.description {
    @apply rounded-md border-t border-b-4 border-slate-700 bg-slate-900/60 px-4 py-5 text-slate-300;
}

.modal-left {
    @apply mx-auto grid grid-cols-1 self-start p-4 py-6 lg:mt-20;
    @apply rounded-md border-2 border-y-4 border-e-0 border-slate-700 bg-slate-900/60;
}

.modal-right {
    @apply grid w-full grid-cols-2 justify-evenly gap-2 pt-2 text-slate-300;
    @apply lg:ms-0 lg:mt-15 lg:grid-cols-1 lg:self-start lg:text-sm;
}

.modal-right ul {
    @apply mt-2 mb-4 space-y-2 rounded-md border-4 border-s-0 border-e-2 border-slate-700 bg-slate-900/60 p-2 py-3 ps-4;
}

.modal-right ul li .list-marker {
    @apply inline-block w-5 text-center;
}

.modal-center {
    @apply mx-auto grid grid-cols-1 items-start gap-0 lg:w-11/12 lg:self-start;
}
</style>
