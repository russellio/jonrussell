<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
const { closeModal, escapeToClose } = useModal();

import { library } from '@fortawesome/fontawesome-svg-core';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
library.add(faXmark);

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
        default: 'Cancel',
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
    <div :id="modalId" class="modal-wrapper">
        <div class="modal" @click.stop>
            <div class="modal-header">
                <h1 v-html="title" />
                <button @click="closeModal(modalId)" aria-label="Close" class="absolute top-0 right-0 m-2 cursor-pointer">
                    <FontAwesomeIcon icon="fa-outline fa-xmark" class="h-6 w-6" />
                </button>
            </div>

            <div class="modal-content">
                <slot />
            </div>

            <div class="modal-footer">
                <button @click="closeModal(modalId)" :disabled="isLoading" class="btn-cancel">{{ props.cancelText }}</button>
                <button v-if="showSubmit" @click="handleSubmit" :disabled="isLoading || submitDisabled" class="btn">
                    <span v-if="isLoading" class="animate-spin">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </span>
                    {{ submitText }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.btn {
    @apply mx-0;
}

.btn-submit {
    @apply flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50;
}
</style>
