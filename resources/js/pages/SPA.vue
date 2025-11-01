<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, nextTick, watch } from 'vue';

import AboutSection from '@/js/sections/AboutSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import Nav from '@/js/layout/Nav.vue';

const { isOpen, openModal } = useModal();
const { scrollToSection } = useScrollToSection();
const page = usePage();
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
    const scrollTo = (page.props as any).scrollTo as string | undefined;
    await performScrollAction(scrollTo);
});

// Watch for route changes
watch(
    () => (page.props as any).scrollTo,
    async (newScrollTo) => {
        await performScrollAction(newScrollTo);
    }
);
</script>

<template>
    <div class="app-layout" id="home">

        <Nav />

        <header>
            <!-- Header / Intro / Navigation -->
            <Header />
        </header>

        <!-- Main content with all sections -->
        <main>
            <!-- About Section -->
            <ContentFrame id="about">
                <AboutSection />
            </ContentFrame>

            <!-- Projects Section -->
            <ContentFrame id="projects">
                <ProjectsSection />
            </ContentFrame>

            <!-- Contact Modal -->
            <ContactModal v-if="isContactOpen" />
        </main>

        <!-- Footer -->
        <Footer ref="footer" />
    </div>
</template>

<style scoped></style>
