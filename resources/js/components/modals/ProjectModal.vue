<script setup lang="ts">
import ImageModal from '@/js/components/modals/ImageModal.vue';
import Modal from '@/js/components/modals/Modal.vue';
import { computed, ref } from 'vue';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowUpRightFromSquare, faAward } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faAward, faArrowUpRightFromSquare);

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const imageModalRef = ref<InstanceType<typeof ImageModal> | null>(null);

const projectHasProp = (project: any, property: any) => {
    if (project.hasOwnProperty(property)) {
        if (typeof project[property] === 'object') {
            return Object.keys(project[property]).length > 0;
        }
    }
    return project.hasOwnProperty(property) && Object.keys(project[property]).length > 0;
};

const hasModalLeft = computed(() => {
    return projectHasProp(props.project, 'primaryImage');
});

const hasModalRight = computed(() => {
    return projectHasProp(props.project, 'skills') || projectHasProp(props.project, 'technologies') || projectHasProp(props.project, 'tools');
});

const title = computed(() => {
    return `<span class='text-secondary!''>project: </span><br class='md:hidden'>${props.project.title}`;
});

const companyLogoSrc = computed(() => {
    return props.project.company?.logo?.src ? `/images/logos/${props.project.company.logo.src}` : null;
});
const companyLogoText = computed(() => {
    return props.project.company?.logo?.displayName ? props.project.company.name : null;
});
</script>

<template>
    <Modal modalId="project-modal" :title="title" cancelText="Close">
        <div class="grid w-full grid-cols-1 lg:grid-cols-[minmax(18%,120px)_auto_18%]">
            <div v-if="hasModalLeft" class="modal-left mb-10" :class="{ '': !projectHasProp(project, 'images') }">
                <div
                    v-if="projectHasProp(project, 'primaryImage')"
                    class="mb-6 cursor-pointer"
                    @click="imageModalRef?.openImageModal(project.primaryImage)"
                >
                    <img
                        :src="`/images/projects/${project.primaryImage.src}`"
                        :title="project.primaryImage.title"
                        :alt="project.primaryImage.alt ?? project.primaryImage.title"
                        class="mx-auto w-4/5 rounded-md object-fill lg:w-full"
                    />
                </div>

                <div v-if="projectHasProp(project, 'images')" class="thumbnails">
                    <div v-for="(image, index) in project.images" :key="index" class="thumbnail" @click="imageModalRef?.openImageModal(image)">
                        <img v-if="image?.src" :src="`/images/projects/${image.src}`" :title="image.title" :alt="image.alt" />
                    </div>
                </div>
            </div>

            <div class="modal-center w-full">
                <div v-if="projectHasProp(project, 'company')" class="company">
                    <div class="flex w-full flex-col font-sans tracking-widest text-white! md:flex-row">
                        <h3>company:</h3>
                        <div v-if="companyLogoSrc" class="flex grow flex-col justify-center py-2 md:flex-row">
                            <div class="align-end pe-4">
                                <img
                                    v-if="companyLogoSrc"
                                    :src="companyLogoSrc"
                                    :alt="project.company.logo.alt || project.company.name"
                                    class="mx-auto h-10"
                                />
                            </div>
                            <div v-if="companyLogoText" class="self-center text-xl" v-html="companyLogoText" />
                        </div>
                        <span v-else class="" v-html="project.company.name" />
                    </div>
                </div>

                <div v-if="projectHasProp(project, 'keyTakeaways')" class="key-takeaways mx-auto mt-5 mb-4 w-11/12">
                    <h4 class="mt-0 mb-2 font-space-mono text-white!">key takeaways:</h4>
                    <div class="rounded-md border-y border-terminal-black-700 bg-black/65 p-2 py-6 ps-8">
                        <ul class="list-disc space-y-2 border-s border-terminal-black-700 ps-10">
                            <li v-for="(takeaway, index) in project.keyTakeaways" :key="index" class="">
                                {{ takeaway }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-if="projectHasProp(project, 'description')" class="">
                    <h3>description:</h3>
                    <div v-html="project.description" class="description" />
                </div>

                <div v-if="projectHasProp(project, 'links')" class="links">
                    <h4 class="mt-0 mb-2 ps-4 font-space-mono text-white!">links:</h4>
                    <ul class="list-disc space-y-2 rounded-md border border-s-4 border-terminal-black-700 bg-black/65 p-2 py-3 ps-10">
                        <li v-for="(link, index) in project.links" :key="index">
                            <a :href="link.url" target="_blank">{{ link.title }}</a>
                            <FontAwesomeIcon :icon="faArrowUpRightFromSquare" class="ps-2 text-gold" size="sm" />
                        </li>
                    </ul>
                </div>
            </div>

            <div v-if="hasModalRight" class="modal-right">
                <div v-if="projectHasProp(project, 'technologies')" class="technologies">
                    <h3>skills:</h3>
                    <ul>
                        <li v-for="(tech, index) in project.technologies" :key="index">
                            {{ tech }}
                        </li>
                    </ul>
                </div>

                <div v-if="projectHasProp(project, 'tools')" class="tools">
                    <h3>tools:</h3>
                    <ul>
                        <li v-for="(tool, index) in project.tools" :key="index">
                            {{ tool }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <ImageModal ref="imageModalRef" />
    </Modal>
</template>

<style scoped>
@reference "@/css/app.css";

h3 {
    @apply mt-3 font-space-mono text-bright-green!;
}

.thumbnails {
    @apply mx-auto mt-1 flex flex-wrap gap-1;
    @apply md:grid md:w-4/5 md:grid-cols-6;
    @apply lg:mt-0 lg:w-full lg:grid-cols-2 lg:gap-2;
}

.thumbnail {
    @apply mx-auto my-1 h-32 w-32 cursor-pointer overflow-hidden border-y border-terminal-black-700;
    @apply rounded-md bg-black/65 md:h-20 md:w-20;
}

.thumbnail img {
    @apply h-full w-full object-cover;
}

.company {
    @apply flex flex-row rounded-md border border-y-4 border-b-0 border-terminal-black-700 bg-black/65 px-4;
}

.description {
    @apply rounded-md border-t border-b-4 border-terminal-black-700 bg-black/65 px-4 py-5;
}

.modal-left {
    @apply mx-auto grid grid-cols-1 self-start p-4 py-6 lg:mt-20;
    @apply rounded-md border-2 border-y-4 border-e-0 border-terminal-black-700 bg-black/65;
}

.modal-right {
    @apply grid w-full grid-cols-2 justify-evenly gap-2 pt-2;
    @apply lg:ms-0 lg:mt-15 lg:grid-cols-1 lg:self-start lg:text-sm;
}

.modal-right ul {
    @apply mt-2 mb-4 list-disc space-y-2 rounded-md border-4 border-s-0 border-e-2 border-terminal-black-700 bg-black/65 p-2 py-3 ps-10;
}

.modal-center {
    @apply mx-auto grid grid-cols-1 items-start gap-0 lg:w-11/12 lg:self-start;
}
</style>
