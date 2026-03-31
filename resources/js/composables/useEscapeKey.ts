import { onMounted, onBeforeUnmount } from 'vue';

export function useEscapeKey(callback: () => void): void {
    const handleKeydown = (event: KeyboardEvent) => {
        if (event.key !== 'Escape') {
            return;
        }

        callback();
    };

    onMounted(() => {
        window.addEventListener('keydown', handleKeydown);
    });

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', handleKeydown);
    });
}
