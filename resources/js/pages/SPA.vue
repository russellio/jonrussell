<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { computed } from 'vue';

import AboutSection from '@/js/sections/AboutSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import Nav from '@/js/layout/Nav.vue';

const { isOpen } = useModal();
const isContactOpen = computed(() => isOpen('contact-modal'));
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
