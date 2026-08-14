import { useModalStore } from '@/js/stores/modalStore';

export function useModal() {
    const modalStore = useModalStore();

    const toggleModal = (modalId: string): void => {
        if (modalStore.isModalOpen(modalId)) {
            modalStore.removeModal(modalId);
        } else {
            modalStore.addModal(modalId);
        }
    };

    const openModal = (modalId: string): void => {
        modalStore.addModal(modalId);
    };

    const closeModal = (modalId: string): void => {
        modalStore.removeModal(modalId);
    };

    const closeAllModals = (): void => {
        modalStore.closeAll();
    };

    const isOpen = (modalId: string): boolean => {
        return modalStore.isModalOpen(modalId);
    };

    return {
        toggleModal,
        openModal,
        closeModal,
        closeAllModals,
        isOpen,
        modalStore,
    };
}
