import { onMounted, onUnmounted, ref } from 'vue';

/**
 * Observes a list of section element ids with an IntersectionObserver and
 * exposes a reactive `activeId` reflecting whichever section is currently
 * most in view, biased toward "the section whose heading has scrolled into
 * the upper-middle of the viewport" via a narrow rootMargin band.
 */
export function useScrollSpy(sectionIds: string[]) {
    const activeId = ref<string | null>(sectionIds[0] ?? null);
    const intersecting = new Map<string, boolean>();

    let observer: IntersectionObserver | null = null;

    const updateActiveId = () => {
        // Sections are listed top-to-bottom in page order. If more than one
        // is intersecting the band at once, prefer the lowest (last) one —
        // it's the one whose heading has most recently scrolled into view.
        for (let i = sectionIds.length - 1; i >= 0; i--) {
            if (intersecting.get(sectionIds[i])) {
                activeId.value = sectionIds[i];
                return;
            }
        }
    };

    onMounted(() => {
        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    intersecting.set(entry.target.id, entry.isIntersecting);
                }
                updateActiveId();
            },
            {
                // Biases toward the upper-middle of the viewport: a section
                // counts as "in view" once its top has crossed 20% down from
                // the viewport top, until its bottom crosses 55% down.
                rootMargin: '-20% 0px -55% 0px',
                threshold: 0,
            },
        );

        for (const id of sectionIds) {
            const element = document.getElementById(id);
            if (element) observer.observe(element);
        }
    });

    onUnmounted(() => {
        observer?.disconnect();
    });

    return { activeId };
}
