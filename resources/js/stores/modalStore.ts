import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
    state: () => ({
        openModals: new Set<string>(),
    }),
    actions: {
        addModal(modalId: string): void {
            this.openModals.add(modalId);
        },
        removeModal(modalId: string): void {
            this.openModals.delete(modalId);
        },
        toggleModal(modalId: string): void {
            if (this.openModals.has(modalId)) {
                this.removeModal(modalId);
            } else {
                this.addModal(modalId);
            }
        },
    },
    getters: {
        isModalOpen: (state) => (modalId: string): boolean => state.openModals.has(modalId),
    },
});
