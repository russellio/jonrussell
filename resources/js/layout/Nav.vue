<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faHouse } from '@fortawesome/free-regular-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
library.add(faHouse);

import { useScrollToSection } from '@/js/composables/useScrollToSection';
const { scrollToSection } = useScrollToSection();

import { useModal } from '@/js/composables/useModal';
const { openModal } = useModal();

const navigation = [
    {
        name: 'About',
        ref: 'about',
    },
    {
        name: 'Projects',
        ref: 'projects',
    },
];
</script>

<template>
    <!-- Navigation -->
    <nav class="sticky top-0 z-999 overflow-visible border-b border-b-terminal-black-700 bg-dark-blue">
        <div class="section-wrapper">
            <div class="flex justify-center">
                <div class="md:block">
                    <button @click="scrollToSection('home')" class="btn-home">
                        <FontAwesomeIcon :icon="faHouse" size="lg" />
                    </button>
                    <button v-for="item in navigation" :key="item.name" @click="scrollToSection(item.ref)" class="primary-nav">
                        {{ item.name }}
                    </button>
                    <button @click="openModal('contact-modal')" class="primary-nav">Contact</button>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
@reference "@/css/app.css";

.btn-home {
    @apply me-4 w-auto flex-none cursor-pointer text-xl text-white opacity-80 hover:opacity-100 md:text-lg;
}

.primary-nav {
    @apply mx-1 my-4 w-auto rounded-lg text-white md:mx-2 md:my-2 md:h-8 md:w-40;
    @apply border-2 border-secondary bg-transparent outline-none;
    @apply cursor-pointer px-2 py-2 font-space-mono text-xl uppercase md:px-2 md:py-0 md:text-lg;
    @apply transition-all duration-400;
}

.primary-nav:hover {
    @apply rounded-xl border-white bg-secondary font-bold;
}
</style>
