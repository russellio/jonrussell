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
    }
];
</script>

<template>
    <!-- Navigation -->
    <nav class="sticky top-0 overflow-visible bg-dark-blue border-b border-b-terminal-black-700 z-[999]">
        <div class="section-wrapper">

            <div class="flex justify-center">
                <div class="md:block">
                    <button @click="scrollToSection('home')" class="btn-home">
                        <FontAwesomeIcon :icon="faHouse" size="lg" />
                    </button>
                    <button v-for="item in navigation" :key="item.name" @click="scrollToSection(item.ref)" class="primary-nav">
                        {{ item.name }}
                    </button>
                    <button @click="openModal('contact-modal')" class="primary-nav">
                        Contact
                    </button>
                </div>

            </div>
        </div>
    </nav>
</template>

<style scoped>
@reference "@/css/app.css";

.btn-home {
    @apply text-xl md:text-lg text-white me-4 opacity-80 hover:opacity-100 flex-none;
}

.primary-nav {
    @apply w-auto md:w-40 md:h-8 text-white mx-1 my-4 md:mx-2 md:my-2 rounded-lg;
    @apply border border-secondary outline-none bg-transparent;
    @apply font-space-mono uppercase text-lg px-2 lg:px-4 py-2 md:px-2 md:py-1 md:text-sm cursor-pointer;
    @apply transition-all duration-400;
}

.primary-nav:hover {
    @apply rounded-xl font-bold border-white;
}
</style>
