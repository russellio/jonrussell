<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';

const { openModal } = useModal();

const socials = [
    { name: 'GitHub', url: 'https://github.com/russellio', icon: 'github' },
    { name: 'LinkedIn', url: 'https://www.linkedin.com/in/russell-jonathan/', icon: 'linkedin' },
];
</script>

<template>
    <ul class="mt-8 flex w-full max-w-xs flex-row items-center justify-center border-t border-t-white/10 pt-2" aria-label="Social media">
        <li v-for="social in socials" :key="social.name" class="mx-5 shrink-0 text-center">
            <UButton
                variant="ghost"
                color="neutral"
                :to="social.url"
                target="_blank"
                rel="noreferrer noopener"
                :aria-label="`${social.name} (opens in a new tab)`"
                :title="social.name"
                class="opacity-50 hover:opacity-100"
            >
                <UIcon :name="`i-lucide-${social.icon}`" class="h-5 w-5" aria-hidden="true" />
            </UButton>
        </li>
        <li class="mx-5 shrink-0 text-center">
            <UButton
                type="button"
                variant="ghost"
                color="neutral"
                aria-label="Email Jon Russell"
                class="opacity-50 hover:opacity-100"
                title="Email"
                @click="openModal('contact-modal')"
            >
                <UIcon name="i-lucide-mail" class="h-5 w-5" aria-hidden="true" />
            </UButton>
        </li>
    </ul>
</template>
