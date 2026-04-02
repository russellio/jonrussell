import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
    state: () => ({
        openModals: new Set<string>(),
    }),
    actions: {
        addModal(modalId: string) {
            this.openModals.add(modalId);
        },
        removeModal(modalId: string) {
            this.openModals.delete(modalId);
        },
        toggleModal(modalId: string) {
            if (this.openModals.has(modalId)) {
                this.removeModal(modalId);
            } else {
                this.addModal(modalId);
            }
        },
        closeAll() {
            this.openModals.clear();
        },
    },
    getters: {
        isModalOpen: (state) => (modalId: string) => state.openModals.has(modalId),
    },
});
