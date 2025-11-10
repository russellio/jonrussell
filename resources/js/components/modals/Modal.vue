<script setup lang="ts">
import { useModal } from '../../composables/useModal';
const { closeModal, escapeToClose } = useModal();

import { library } from '@fortawesome/fontawesome-svg-core';
import { faHeadphones } from '@fortawesome/free-regular-svg-icons';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faXmark, faHeadphones);

const emit = defineEmits(['submit']);

const props = defineProps({
    modalId: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: false,
        default: '',
    },
    showSubmit: {
        type: Boolean,
        required: false,
        default: false,
    },
    submitDisabled: {
        type: Boolean,
        default: false,
    },
    submitText: {
        type: String,
        default: 'Submit',
    },
    cancelText: {
        type: String,
        default: 'Close',
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

const modalId = props.modalId;
const title = props.title ?? props.modalId.toUpperCase();

const handleSubmit = () => {
    if (!props.isLoading) {
        emit('submit');
    }
};

escapeToClose(modalId);
</script>

<template>
    <div :id="modalId" class="modal-wrapper" @click="closeModal(modalId)">
        <div class="modal" @click.stop>
            <div class="modal-header">
                <h1 v-html="title" />
                <button @click="closeModal(modalId)" aria-label="Close" class="absolute top-0 right-0 m-2 cursor-pointer">
                    <FontAwesomeIcon :icon="faXmark" class="bg-blur-sm m-1 h-6 w-6 rounded-md border border-white bg-white/50 p-2 text-black" />
                </button>
            </div>

            <div class="modal-content">
                <slot />
            </div>

            <div class="modal-footer">
                <button @click="closeModal(modalId)" class="btn-cancel" v-html="props.cancelText" />
                <button v-if="showSubmit" @click="handleSubmit" :disabled="isLoading || submitDisabled" class="btn-submit">
                    <span v-if="isLoading" class="animate-spin">
                        <FontAwesomeIcon :icon="faHeadphones" class="h-5 w-5 opacity-75" />
                    </span>
                    <span v-html="props.submitText" />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";
.modal-header {
    @apply flex items-center justify-between p-4 pb-2;
}

.modal-header h1 {
    @apply align-top text-4xl font-extrabold text-primary;
}

.modal-header h1 span {
    @apply font-sixtyfour text-2xl font-normal text-secondary!;
}

.modal-content {
    @apply flex w-full flex-col bg-[#222] p-4 pt-2 text-white md:flex-row;
}

.modal-footer {
    @apply flex flex-row justify-end gap-2 p-4 lg:pe-20;
}
</style>
