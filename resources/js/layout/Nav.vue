<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import { useScrollSpy } from '@/js/composables/useScrollSpy';
import { useScrollToSection } from '@/js/composables/useScrollToSection';

const navigation = [
    { name: 'About', ref: 'about' },
    { name: 'Tech Stack', ref: 'tech-stack' },
    { name: 'Skills', ref: 'skills' },
    { name: 'Experience', ref: 'experience' },
    { name: 'Projects', ref: 'projects' },
    { name: 'Writing', ref: 'writing' },
];

const { activeId: activeSection } = useScrollSpy(navigation.map((item) => item.ref));
const { scrollToSection } = useScrollToSection();
const { openModal } = useModal();
</script>

<template>
    <nav class="nav hidden lg:block" aria-label="In-page jump links">
        <ul class="mt-16 w-max">
            <li v-for="item in navigation" :key="item.ref">
                <a
                    class="group flex items-center py-3"
                    :class="{ active: activeSection === item.ref }"
                    :href="`#${item.ref}`"
                    @click.prevent="scrollToSection(item.ref)"
                >
                    <span
                        class="nav-indicator mr-4 h-px w-8 bg-slate-600 transition-all group-hover:w-16 group-hover:bg-slate-200 group-focus-visible:w-16 group-focus-visible:bg-slate-200 motion-reduce:transition-none"
                    ></span>
                    <span
                        class="nav-text text-xs font-bold tracking-widest text-slate-500 uppercase group-hover:text-slate-200 group-focus-visible:text-slate-200"
                    >
                        {{ item.name }}
                    </span>
                </a>
            </li>
            <li>
                <button type="button" class="group flex w-full items-center py-3 text-left" @click="openModal('contact-modal')">
                    <span
                        class="nav-indicator mr-4 h-px w-8 bg-slate-600 transition-all group-hover:w-16 group-hover:bg-slate-200 group-focus-visible:w-16 group-focus-visible:bg-slate-200 motion-reduce:transition-none"
                    ></span>
                    <span
                        class="nav-text text-xs font-bold tracking-widest text-slate-500 uppercase group-hover:text-slate-200 group-focus-visible:text-slate-200"
                    >
                        Contact
                    </span>
                </button>
            </li>
        </ul>
    </nav>
</template>
