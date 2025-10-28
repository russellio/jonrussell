<script setup lang="ts">
import Modal from '@/js/components/Modal.vue';
import ImageModal from '@/js/components/modals/ImageModal.vue';
import { ref } from 'vue';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowUpRightFromSquare, faAward, faCaretRight } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faAward, faCaretRight, faArrowUpRightFromSquare);

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const imageModalRef = ref<InstanceType<typeof ImageModal> | null>(null);

const projectHasProp = (project: any, property: any) => {
    return project.hasOwnProperty(property) && project[property].length > 0;
};

const hasModalLeft = (project: any) => {
    return projectHasProp(project, 'primaryImage');
};

const hasModalRight = (project: any) => {
    return projectHasProp(project, 'skills') || projectHasProp(project, 'technologies') || projectHasProp(project, 'tools');
};

const title = `<span>project: </span><br class='md:hidden'>${props.project.title}`;
</script>

<template>
    <Modal modalId="project-modal" :title="title">
        <div v-if="hasModalLeft(project)" class="modal-left">
            <div v-if="projectHasProp(project, 'primaryImage')" class="primary-image" @click="imageModalRef?.openImageModal(project.primaryImage)">
                <img :src="`/images/projects/${project.primaryImage}`" :title="project.primaryImage.title" :alt="project.primaryImage.alt ?? ''" />
            </div>
            <div v-if="projectHasProp(project, 'images')" class="thumbnails">
                <div v-for="(image, index) in project.images" :key="index" class="thumbnail" @click="imageModalRef?.openImageModal(image)">
                    <img v-if="image?.src" :src="`/images/projects/${image.src}`" :title="image.title" :alt="image.alt" />
                </div>
            </div>
        </div>

        <div class="modal-center">
            <div v-if="projectHasProp(project, 'company')" class="company">
                <h3>
                    company:
                    <span class="font-space-mono font-normal !text-white">{{ project.company }}</span>
                </h3>
            </div>

            <div v-if="projectHasProp(project, 'description')" class="description">
                <h3>description:</h3>
                <div v-html="project.description" class="" />
            </div>

            <div v-if="projectHasProp(project, 'links')" class="links">
                <h3>links:</h3>
                <ul class="fa-ul" style="list-style-type: disc;">
                    <li v-for="(link, index) in project.links" :key="index">
                        <a :href="link.url" target="_blank">{{ link.title }}</a>
                        <FontAwesomeIcon :icon="faArrowUpRightFromSquare" class="ps-2 text-gold" size="sm" />
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="hasModalRight(project)" class="modal-right">
            <div v-if="projectHasProp(project, 'skills')" class="skills">
                <h3>skills:</h3>
                <ul class="fa-ul">
                    <li v-for="(skill, index) in project.skills" :key="index">
                        <span class="fa-li"><FontAwesomeIcon :icon="faCaretRight" /></span>
                        {{ skill }}
                    </li>
                </ul>
            </div>

            <div v-if="projectHasProp(project, 'technologies')" class="technologies">
                <h3>tech:</h3>
                <ul class="fa-ul">
                    <li v-for="(tech, index) in project.technologies" :key="index">
                        <span class="fa-li"><FontAwesomeIcon :icon="faCaretRight" /></span>
                        {{ tech }}
                    </li>
                </ul>
            </div>

            <div v-if="projectHasProp(project, 'tools')" class="tools">
                <h3>tools:</h3>
                <ul class="fa-ul">
                    <li v-for="(tool, index) in project.tools" :key="index">
                        <span class="fa-li"><FontAwesomeIcon :icon="faCaretRight" /></span>
                        {{ tool }}
                    </li>
                </ul>
            </div>
        </div>

        <ImageModal ref="imageModalRef" />
    </Modal>
</template>

<style scoped>
@reference "@/css/app.css";

h3 {
    @apply mt-3 font-space-mono text-lg font-bold;
}

.primary-image img {
    width: 100%;
    object-fit: fill;
}

.thumbnails {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.25rem;
}

.thumbnail {
    margin-top: 0.25rem;
    max-height: 80px;
    max-width: 80px;
    cursor: pointer;
    overflow: hidden;
}
</style>
