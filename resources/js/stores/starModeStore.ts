import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

const STORAGE_KEY = 'starMode';

export const useStarModeStore = defineStore('starMode', () => {
    const showStars = ref(false);

    function hydrate(): void {
        if (typeof window === 'undefined') {
            return;
        }

        showStars.value = window.localStorage.getItem(STORAGE_KEY) === 'true';
    }

    watch(showStars, (v) => {
        if (typeof window !== 'undefined') {
            window.localStorage.setItem(STORAGE_KEY, String(v));
        }
    });

    return { showStars, hydrate };
});
