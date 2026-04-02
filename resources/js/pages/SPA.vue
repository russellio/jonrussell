<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, nextTick, watch, defineAsyncComponent } from 'vue';

import AboutSection from '@/js/sections/AboutSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import Nav from '@/js/layout/Nav.vue';

const ScrollingThingsILike = defineAsyncComponent(() => import('@/js/components/ScrollingThingsILike.vue'));

const { isOpen, openModal } = useModal();
const { scrollToSection } = useScrollToSection();

type PageProps = {
    scrollTo?: string;
};

const page = usePage<PageProps>();
const isContactOpen = computed(() => isOpen('contact-modal'));

const performScrollAction = async (scrollTo: string | undefined) => {
    if (!scrollTo) return;

    await nextTick();

    setTimeout(() => {
        if (scrollTo === 'contact') {
            openModal('contact-modal');
        } else {
            scrollToSection(scrollTo);
        }
    }, 100);
};

onMounted(async () => {
    const scrollTo = page.props.scrollTo;
    await performScrollAction(scrollTo);
});

watch(
    () => page.props.scrollTo,
    async (newScrollTo) => {
        await performScrollAction(newScrollTo);
    }
);
</script>

<template>
    <div class="app-layout">

        <Nav />

        <header id="home">
            <Header />
        </header>

        <main>
            <ContentFrame id="about">
                <AboutSection />
            </ContentFrame>

            <ScrollingThingsILike />

            <ContentFrame id="projects">
                <ProjectsSection />
            </ContentFrame>

            <ContactModal v-if="isContactOpen" />
        </main>

        <Footer ref="footer" />
    </div>
</template>

<style scoped></style>
