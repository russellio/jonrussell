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

const title = `<span>project: </span><br class='lg:hidden'>${props.project.title}`;
</script>

<template>
    <Modal modalId="project-modal" :title="title">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(18%,120px)_auto_18%] w-full">

            <div v-if="hasModalLeft(project)"
                 class="modal-left"
                 :class="{'no-thumbnails': !projectHasProp(project, 'images')}"
            >
                <div v-if="projectHasProp(project, 'primaryImage')"
                     class="primary-image cursor-pointer"
                     @click="imageModalRef?.openImageModal(project.primaryImage)">
                    <img :src="`/images/projects/${project.primaryImage}`" :title="project.primaryImage.title" :alt="project.primaryImage.alt ?? project.primaryImage.title" />
                </div>
                <div v-if="projectHasProp(project, 'images')" class="thumbnails">
                    <div v-for="(image, index) in project.images"
                         :key="index"
                         class="thumbnail cursor-pointer"
                         @click="imageModalRef?.openImageModal(image)">
                        <img v-if="image?.src" :src="`/images/projects/${image.src}`" :title="image.title" :alt="image.alt" />
                    </div>
                </div>
            </div>

            <div class="modal-center w-full">
                <div v-if="projectHasProp(project, 'company')" class="company">
                    <h3>
                        company:
                        <span class="font-sans !text-white" v-html="project.company" />
                    </h3>
                </div>

                <div v-if="projectHasProp(project, 'keyTakeaways')" class="key-takeaways">
                    <h3>key takeaways:</h3>
                    <ul>
                        <li v-for="(takeaway, index) in project.keyTakeaways" :key="index">
                            {{ takeaway }}
                        </li>
                    </ul>
                </div>

                <div v-if="projectHasProp(project, 'description')" class="description">
                    <h3>description:</h3>
                    <div v-html="project.description" />
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
    @apply flex flex-wrap gap-1 mx-auto mt-1 content-start;
    @apply lg:grid lg:grid-cols-2 lg:gap-2 lg:w-full;
}

.thumbnail {
    @apply cursor-pointer max-w-[8rem] max-h-[8rem] md:h-[5rem] md:w-[5rem] my-1 mx-auto;
    /*margin-top: 0.25rem;*/
    /*max-height: 60px;*/
    /*max-width: 90px;*/
    overflow: hidden;
}
.no-thumbnails {
    @apply w-50 items-center;
}


.key-takeaways {
    @apply mt-5 mb-4;
}

.key-takeaways h3 {
    @apply text-lg text-white mt-0 mb-2 ps-4;
}

.key-takeaways ul {
    @apply ps-10 rounded border-l-1 border-terminal-black-700;
}

.key-takeaways ul li {
    @apply py-2;
    list-style-type: disc;
}
</style>
