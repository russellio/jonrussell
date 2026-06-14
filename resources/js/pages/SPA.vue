<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import type { AppPageProps } from '@/js/types/index';
import { usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, nextTick, onMounted, ref, watch } from 'vue';

import Nav from '@/js/layout/Nav.vue';
import AboutSection from '@/js/sections/AboutSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';

const ScrollingThingsILike = defineAsyncComponent(() => import('@/js/components/ScrollingThingsILike.vue'));

const { isOpen, openModal } = useModal();
const { scrollToSection } = useScrollToSection();

type PageProps = AppPageProps<{ scrollTo?: string }>;

const page = usePage<PageProps>();
const isContactOpen = computed(() => isOpen('contact-modal'));

const readySections = ref(new Set<string>(['home']));
const handleSectionReady = (id: string) => readySections.value.add(id);

const performScrollAction = async (scrollTo: string | undefined) => {
    if (!scrollTo) return;
    if (scrollTo === 'contact') {
        openModal('contact-modal');
        return;
    }
    if (!readySections.value.has(scrollTo)) {
        let timedOut = false;
        await new Promise<void>((resolve) => {
            const timeout = setTimeout(() => {
                timedOut = true;
                stop();
                resolve();
            }, 1500);
            const stop = watch(
                readySections,
                (set) => {
                    if (set.has(scrollTo)) {
                        clearTimeout(timeout);
                        stop();
                        resolve();
                    }
                },
                { deep: true },
            );
        });
        if (timedOut) {
            await nextTick();
            scrollToSection('home');
            return;
        }
    }
    await nextTick();
    scrollToSection(scrollTo);
};

onMounted(async () => {
    const scrollTo = page.props.scrollTo;
    await performScrollAction(scrollTo);
});

watch(
    () => page.props.scrollTo,
    async (newScrollTo) => {
        await performScrollAction(newScrollTo);
    },
);
</script>

<template>
    <div class="app-layout">
        <Nav />

        <header id="home">
            <Header />
        </header>

        <main>
            <ContentFrame id="about" @ready="handleSectionReady">
                <AboutSection />
            </ContentFrame>

            <ScrollingThingsILike />

            <ContentFrame id="projects" @ready="handleSectionReady">
                <ProjectsSection />
            </ContentFrame>

            <ContactModal v-if="isContactOpen" />
        </main>

        <Footer ref="footer" />
    </div>
</template>
