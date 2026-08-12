<script setup lang="ts">
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import SpaceMode from '@/js/layout/SpaceMode.vue';
import AboutSection from '@/js/sections/AboutSection.vue';
import ExperienceSection from '@/js/sections/ExperienceSection.vue';
import PostsSection from '@/js/sections/PostsSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import SkillsSection from '@/js/sections/SkillsSection.vue';
import TechStackSection from '@/js/sections/TechStackSection.vue';
import type { AppPageProps } from '@/js/types/index';
import { usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, watch } from 'vue';

type PageProps = AppPageProps<{ scrollTo?: string }>;

const page = usePage<PageProps>();
const { isOpen, openModal } = useModal();
const { scrollToSection } = useScrollToSection();

const isContactOpen = computed(() => isOpen('contact-modal'));

const delay = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

const performScrollAction = async (scrollTo: string | undefined) => {
    if (!scrollTo) return;

    if (scrollTo === 'contact') {
        openModal('contact-modal');
        return;
    }

    // Wait for the target section element to exist.
    await nextTick();
    const existsDeadline = Date.now() + 1500;
    let target = document.getElementById(scrollTo);
    while (!target && Date.now() < existsDeadline) {
        await delay(50);
        target = document.getElementById(scrollTo);
    }
    if (!target) return;

    // Sections fetch their own data on mount and grow afterwards, which shifts
    // the layout. Scroll once for responsiveness, wait for the target's position
    // to settle (all sections loaded), then correct to the final position.
    scrollToSection(scrollTo);

    const settleDeadline = Date.now() + 1500;
    let lastTop = Number.NaN;
    let stableTicks = 0;
    while (Date.now() < settleDeadline && stableTicks < 3) {
        await delay(100);
        const top = Math.round(target.getBoundingClientRect().top);
        stableTicks = Math.abs(top - lastTop) <= 1 ? stableTicks + 1 : 0;
        lastTop = top;
    }

    scrollToSection(scrollTo);
};

onMounted(async () => {
    await performScrollAction(page.props.scrollTo);
});

watch(
    () => page.props.scrollTo,
    async (newScrollTo) => {
        await performScrollAction(newScrollTo);
    },
);
</script>

<template>
    <div>
        <SpaceMode />

        <div class="mx-auto min-h-screen max-w-screen-xl px-6 py-12 font-sans md:px-8 md:py-8 md:pt-0 lg:py-0">
            <!-- <a
                href="#content"
                class="absolute top-0 left-0 block -translate-x-full rounded bg-yellow-500 px-4 py-3 text-sm font-bold tracking-widest text-slate-900 uppercase focus-visible:translate-x-0 focus-visible:text-slate-900"
                >Skip to Content</a
            > -->
            <div class="lg:flex lg:justify-between lg:gap-4">
                <Header />
                <main id="content" class="pt-12 lg:w-[68%] lg:py-14 lg:pt-24">
                    <div>
                        <AboutSection />
                        <TechStackSection />
                        <SkillsSection />
                        <ExperienceSection />
                        <ProjectsSection />
                        <PostsSection />
                    </div>
                    <Footer />
                </main>
            </div>
        </div>

        <ContactModal v-if="isContactOpen" />
    </div>
</template>
