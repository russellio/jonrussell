<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { computed, ref } from 'vue';

// Import section components
import AboutSection from '@/js/sections/AboutSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import Nav from '@/js/layout/Nav.vue';

const { isOpen } = useModal();
const isContactOpen = computed(() => isOpen('contact-modal'));

// Refs for sections to enable scrollToSection functionality
const aboutSection = ref(null);
const projectsSection = ref(null);

// Expose scrollToSection function globally
const scrollToSection = (sectionName: string) => {
    console.log('scrollToSection() - ', sectionName);

    let targetElement = null;

    switch (sectionName) {
        case 'about':
            targetElement = aboutSection.value?.$el;
            break;
        case 'projects':
            targetElement = projectsSection.value?.$el;
            break;
    }

    if (targetElement) {
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
};

// Make scrollToSection available globally
if (typeof window !== 'undefined') {
    (window as any).scrollToSection = scrollToSection;
}
</script>

<template>
    <div class="app-layout">

        <Nav ref="navigation" />

        <header>
            <!-- Header / Intro / Navigation -->
            <Header ref="header" />
        </header>

        <!-- Main content with all sections -->
        <main ref="main">
            <!-- About Section -->
            <ContentFrame id="about">
                <AboutSection ref="aboutSection" />
            </ContentFrame>

            <!-- Projects Section -->
            <ContentFrame>
                <ProjectsSection ref="projectsSection" />
            </ContentFrame>

            <!-- Contact Modal -->
            <ContactModal v-if="isContactOpen" />
        </main>

        <!-- Footer -->
        <Footer ref="footer" />
    </div>
</template>

<style scoped></style>
