<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import About from '@/js/pages/About.vue';
import { computed } from 'vue';
import Projects from '@/js/pages/Projects.vue';
import Nav from '@/js/layout/Nav.vue';

const { isOpen } = useModal();
const isContactOpen = computed(() => isOpen('contact-modal'));
</script>

<template>

    <div class="app-layout">
        <header>
            <!-- Header / Intro / Navigation -->
            <Header ref="header" />
        </header>

        <!-- Main content -->
        <main ref="main">
<!--            <ContentFrame>-->
<!--                <About ref="about" />-->
<!--            </ContentFrame>-->

            <ContentFrame>
                <slot />
            </ContentFrame>

            <!-- Contact Modal -->
            <ContactModal v-if="isContactOpen" />
        </main>

        <!-- Footer -->
        <Footer ref="footer" />
    </div>
</template>

<style scoped></style>
