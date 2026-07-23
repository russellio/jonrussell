import { onMounted, onUnmounted, type Ref } from 'vue';

/**
 * Tracks the mouse position and writes it to CSS custom properties
 * (`--mouse-x` / `--mouse-y`) on the given element, so a CSS
 * `radial-gradient` (or similar) can follow the cursor.
 *
 * Coordinates are written directly via `element.style.setProperty(...)`
 * rather than through reactive Vue state, so a mousemove doesn't trigger
 * a Vue re-render on every frame — the browser's CSS engine consumes the
 * custom properties directly.
 */
export function useSpotlight(target: Ref<HTMLElement | null>) {
    const handleMouseMove = (event: MouseEvent) => {
        const element = target.value;
        if (!element) return;

        // The gradient position is relative to the element's own box, not
        // the viewport, so client coordinates must be adjusted by the
        // element's current bounding-rect origin. On mobile the element is
        // `fixed` (rect.top/left ≈ 0), so this reduces to the raw client
        // coordinates. On desktop it's `absolute` inside a full-document
        // wrapper, so the rect origin shifts with scroll position.
        const { left, top } = element.getBoundingClientRect();
        element.style.setProperty('--mouse-x', `${event.clientX - left}px`);
        element.style.setProperty('--mouse-y', `${event.clientY - top}px`);
    };

    onMounted(() => {
        window.addEventListener('mousemove', handleMouseMove);
    });

    onUnmounted(() => {
        window.removeEventListener('mousemove', handleMouseMove);
    });
}
