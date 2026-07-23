<script setup lang="ts">
import { socials } from '@/js/data/socials';
import type { IconDefinition } from '@fortawesome/fontawesome-svg-core';
import { faCodepen, faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import type { Component } from 'vue';
import { GitHubIcon, GoodreadsIcon, InstagramIcon } from 'vue3-simple-icons';

// vue3-simple-icons (installed: 16.10.0) has no LinkedIn or CodePen icon —
// those two render via FontAwesome brand icons instead. The other three
// icon keys map to vue3-simple-icons components.
const faIcons: Partial<Record<string, IconDefinition>> = {
    linkedin: faLinkedin,
    codepen: faCodepen,
};

const simpleIcons: Partial<Record<string, Component>> = {
    github: GitHubIcon,
    instagram: InstagramIcon,
    goodreads: GoodreadsIcon,
};
</script>

<template>
    <ul class="mt-8 ml-1 flex items-center" aria-label="Social media">
        <li v-for="social in socials" :key="social.icon" class="mr-5 shrink-0 text-xs">
            <a
                class="block"
                :href="social.url"
                target="_blank"
                rel="noreferrer noopener"
                :aria-label="`${social.name} (opens in a new tab)`"
                :title="social.name"
            >
                <span class="sr-only">{{ social.name }}</span>
                <FontAwesomeIcon v-if="faIcons[social.icon]" :icon="faIcons[social.icon]!" class="h-6 w-6" aria-hidden="true" />
                <component :is="simpleIcons[social.icon]" v-else class="h-6 w-6" aria-hidden="true" />
            </a>
        </li>
    </ul>
</template>
