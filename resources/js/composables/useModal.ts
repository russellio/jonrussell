import { useModalStore } from '@/js/stores/modalStore';
import { useEscapeKey } from '@/js/composables/useEscapeKey';

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
        modalStore.openModals.clear();
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

    const escapeToClose = (modalId: string): void => {
      useEscapeKey(() => closeModal(modalId));
    };

    return {
        toggleModal,
        openModal,
        closeModal,
        closeAllModals,
        isOpen,
        modalStore,
        escapeToClose,
    };
}
