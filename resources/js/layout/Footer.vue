<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
const { openModal } = useModal();
</script>

<template>
    <!-- Footer -->
    <footer>
        <div class="section-wrapper">
            <p class="text-green text-center">
                &copy; {{ new Date().getFullYear() }} <a role="button" @click="openModal('contact-modal')" class="cursor-pointer">Jon Russell</a>. All
                rights reserved.
            </p>
        </div>
    </footer>
</template>

<style scoped>
@reference "@/css/app.css";

footer {
    @apply py-8 text-terminal-black-500;
}
</style>
