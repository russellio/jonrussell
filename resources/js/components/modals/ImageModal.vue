<script setup lang="ts">
import type { Image } from '@/js/types';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';

const imageModalWrapper = ref<HTMLElement | null>(null);
const isImageModalOpen = ref(false);
const currentImage = ref<Image | null>(null);

const openImageModal = (image: Image) => {
    if (!image?.src) return;
    currentImage.value = image;
    isImageModalOpen.value = true;

    if (!imageModalWrapper.value) return;

    imageModalWrapper.value?.scrollIntoView({ behavior: 'smooth' });
};

const closeImageModal = () => {
    isImageModalOpen.value = false;
};

defineExpose({
    openImageModal,
    closeImageModal,
});
</script>

<template>
    <div ref="imageModalWrapper" class="image-modal-wrapper" :class="isImageModalOpen ? 'flex' : 'hidden'" @click="closeImageModal()">
        <div class="image-modal" @click.stop>
            <button @click="closeImageModal()" aria-label="Close">
                <FontAwesomeIcon
                    :icon="faXmark"
                    class="bg-blur-sm m-1 h-4 w-4 rounded-md border border-slate-600 bg-slate-700/60 p-2 text-slate-200 transition-colors hover:border-teal-300/50 hover:text-teal-300 motion-reduce:transition-none"
                />
            </button>
            <img v-if="currentImage?.src" :src="`/images/projects/${currentImage.src}`" :title="currentImage.title" :alt="currentImage.alt" />
        </div>
    </div>
</template>

<style scoped>
.image-modal-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.75);
    padding: 1rem;
    height: 100vh;
    overflow-y: hidden;
}

.image-modal-wrapper.hidden {
    display: none !important;
    pointer-events: none;
}

.image-modal {
    position: relative;
    max-height: 100%;
    max-width: 48rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(51, 65, 85, 1);
    background-color: rgba(30, 41, 59, 1);
    padding: 0.5rem;
}

.image-modal-wrapper button {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
}
</style>
