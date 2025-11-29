<script setup lang="ts">
import ContentFrame from '@/js/components/ContentFrame.vue';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { computed } from 'vue';

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
