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

const iconClass = 'block h-10 w-10 text-slate-400 transition-colors hover:text-teal-300 focus-visible:text-teal-300';
</script>

<template>
    <ul class="mt-8 flex justify-center" aria-label="Social media">
        <li v-for="social in socials" :key="social.name" class="mx-5 shrink-0 text-center">
            <a
                :class="iconClass"
                :href="social.url"
                target="_blank"
                rel="noreferrer noopener"
                :aria-label="`${social.name} (opens in a new tab)`"
                :title="social.name"
            >
                <span class="sr-only">{{ social.name }}</span>
                <FontAwesomeIcon :icon="social.icon" class="h-6 w-6" aria-hidden="true" />
            </a>
        </li>
        <li class="mx-5 shrink-0 text-center">
            <button type="button" :class="iconClass" aria-label="Email Jon Russell" title="Email" @click="openModal('contact-modal')">
                <span class="sr-only">Email</span>
                <FontAwesomeIcon :icon="faEnvelope" class="h-6 w-6" aria-hidden="true" />
            </button>
        </li>
    </ul>
</template>
