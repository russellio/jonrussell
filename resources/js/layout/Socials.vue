<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import { faGithub, faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope } from '@fortawesome/free-regular-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const { openModal } = useModal();

const socials = [
    { name: 'GitHub', url: 'https://github.com/russellio', icon: faGithub },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/russell-jonathan/', icon: faLinkedin },
];
</script>

<template>
    <ul class="mt-8 flex justify-center" aria-label="Social media">
        <li v-for="social in socials" :key="social.name" class="mx-5 shrink-0 text-center">
            <UButton
                variant="ghost"
                color="neutral"
                :to="social.url"
                target="_blank"
                rel="noreferrer noopener"
                :aria-label="`${social.name} (opens in a new tab)`"
                :title="social.name"
            >
                <FontAwesomeIcon :icon="social.icon" class="h-8 w-8" aria-hidden="true" />
            </UButton>
        </li>
        <li class="mx-5 shrink-0 text-center">
            <UButton type="button" variant="ghost" color="neutral" aria-label="Email Jon Russell" title="Email" @click="openModal('contact-modal')">
                <FontAwesomeIcon :icon="faEnvelope" class="h-8 w-8" aria-hidden="true" />
            </UButton>
        </li>
    </ul>
</template>
