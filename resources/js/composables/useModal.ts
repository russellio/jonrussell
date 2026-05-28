import { useModalStore } from '@/js/stores/modalStore';

export function useModal() {
    const modalStore = useModalStore();

    const toggleModal = (modalId: string): void => {
        if (modalStore.isModalOpen(modalId)) {
            modalStore.removeModal(modalId);
            removeFocusTrap();
        } else {
            modalStore.addModal(modalId);
            addFocusTrap();
        }
    };

    const openModal = (modalId: string): void => {
        modalStore.addModal(modalId);
        addFocusTrap();
    };

    const closeModal = (modalId: string): void => {
        modalStore.removeModal(modalId);
        removeFocusTrap();
    };

    const closeAllModals = (): void => {
        modalStore.closeAll();
        removeFocusTrap();
    };

    const isOpen = (modalId: string): boolean => {
        return modalStore.isModalOpen(modalId);
    };

    function addFocusTrap(): void {
        document.body.style.overflow = 'hidden';
    }

    function removeFocusTrap(): void {
        document.body.style.overflow = '';
    }

    return {
        toggleModal,
        openModal,
        closeModal,
        closeAllModals,
        isOpen,
        modalStore,
    };
}
