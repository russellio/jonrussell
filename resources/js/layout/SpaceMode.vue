<script setup lang="ts">
import { useStarMode } from '@/js/composables/useStarMode';
import { BackgroundStars } from '@russellio/vue-background-stars';

const { showStars } = useStarMode();
</script>

<template>
    <div>
        <div
            class="fixed right-0 bottom-0 left-0 z-40 flex flex-row justify-items-start border-t border-t-brand-red/50 bg-black/50 ps-10 pt-3 pb-3 font-space-mono text-sm font-bold text-white md:block"
        >
            <USwitch label="space mode:" v-model="showStars" color="secondary" />
        </div>

        <div class="fallback-background" :class="{ 'fade-out': showStars }"></div>

        <Transition name="background-fade" appear>
            <div v-if="showStars">
                <BackgroundStars />
                <!-- <Mountains /> -->
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fallback-background {
    position: fixed;
    height: 100%;
    width: 100%;
    overflow: hidden;
    z-index: -2;
    background:
        radial-gradient(at 51% 46%, #0f172a 0, transparent 50%), radial-gradient(at 85% 99%, #330509 0, transparent 50%),
        radial-gradient(at 18% 22%, #111b4f 0, transparent 50%), #0f172a;
    transition: opacity 0.5s ease-in-out;
    opacity: 1;
}

.fallback-background.fade-out {
    opacity: 0;
}

.background-fade-enter-active {
    transition: opacity 0.5s ease-in-out;
}

.background-fade-enter-from {
    opacity: 0;
}

.background-fade-enter-to {
    opacity: 1;
}
</style>
