import { onMounted, onBeforeUnmount } from 'vue';

export function useEscapeKey(callback): void {
  const handleKeydown = (event) => {
    if (event.keyCode === 27) {
      callback();
    }
  };
  onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
  });
  onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown);
  });
}
