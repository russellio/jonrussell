import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
    state: () => ({
        openModals: new Set(),
    }),
    actions: {
        addModal(modalId) {
            this.openModals.add(modalId);
        },
        removeModal(modalId) {
            this.openModals.delete(modalId);
        },
        toggleModal(modalId) {
            if (this.openModals.has(modalId)) {
                this.removeModal(modalId);
            } else {
                this.addModal(modalId);
            }
        },
    },
    getters: {
        isModalOpen: (state) => (modalId) => state.openModals.has(modalId),
    },
});
