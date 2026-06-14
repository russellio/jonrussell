import { ref, watch } from 'vue';

const STORAGE_KEY = 'starMode';

const initial = typeof window !== 'undefined' && window.localStorage.getItem(STORAGE_KEY) === 'true';
const showStars = ref(initial);

if (typeof window !== 'undefined') {
    watch(showStars, (v) => window.localStorage.setItem(STORAGE_KEY, String(v)));
}

export function useStarMode() {
    return { showStars };
}
