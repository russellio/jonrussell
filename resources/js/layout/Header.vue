<script setup lang="ts">
import ToggleSwitch from '@/js/components/ToggleSwitch.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGithub, faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faObjectGroup } from '@fortawesome/free-regular-svg-icons';
import { faCertificate, faLocationDot, faUserShield } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { defineAsyncComponent, ref } from 'vue';
library.add(faLocationDot, faLinkedin, faEnvelope, faObjectGroup, faUserShield, faCertificate, faGithub);

const { scrollToSection } = useScrollToSection();
const { openModal } = useModal();

const BackgroundStars = defineAsyncComponent(() => import('@/js/components/BackgroundStars.vue'));
const showBackground = ref(false);
</script>

<template>
    <div class="fixed top-0 -right-2 z-999 hidden font-space-mono text-sm text-white md:block lg:top-4 lg:right-10">
        <ToggleSwitch label="space mode:" v-model="showBackground" />
    </div>

    <div class="fallback-background" :class="{ 'fade-out': showBackground }"></div>

    <Transition name="background-fade" appear>
        <BackgroundStars v-if="showBackground" />
    </Transition>

    <div class="intro-wrapper mx-auto flex min-h-[calc(100vh-80px)] items-center justify-center px-2 py-6 md:px-6">
        <div class="intro-backdrop hidden"></div>

        <div class="intro-content grid max-w-[768px] grid-cols-1">
            <div class="flex flex-col md:flex-row">
                <div class="intro text-center md:text-start">
                    <h1 class="welcome">Welcome!</h1>

                    <h1 class="mt-2 flex flex-col pb-8 md:flex-row">
                        <span class="clause-head">I'm</span>
                        <span class="name text-outline">Jon Russell</span>
                    </h1>

                    <p class="md:me-0 lg:me-20">
                        A <strong>full stack software engineer</strong>
                        who creates robust, scalable solutions that power modern web applications.
                    </p>
                </div>

                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-backdrop"></div>
                    <img src="/images/profile-avatar.svg" alt="Jon Russell" class="profile-avatar" />
                    <div class="profile-addon-wrapper">
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faLocationDot" size="sm" class="me-1" />
                            Remote - North Carolina
                        </div>
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faCertificate" size="sm" class="me-1" />
                            Certified ScrumMaster
                        </div>
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faUserShield" size="sm" class="me-1" />
                            Confidential Security Clearance
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="mx-auto mt-8 flex max-w-[600px] flex-col items-center justify-between gap-8 md:flex-row">
                    <div class="flex gap-2 lg:gap-4">
                        <button class="secondary-nav" @click="openModal('contact-modal')">
                            <FontAwesomeIcon :icon="faEnvelope" size="lg" class="me-3" />
                            Contact Me
                        </button>
                        <button class="secondary-nav" @click="scrollToSection('projects')">
                            <FontAwesomeIcon :icon="faObjectGroup" size="lg" class="me-3" />
                            View Projects
                        </button>
                    </div>

                    <div data-orientation="vertical" role="none" class="hidden h-5 w-[2px] shrink-0 bg-white md:block"></div>

                    <div class="flex gap-4 px-5 pb-10 text-xl sm:pb-0 md:gap-8 md:px-0">
                        <a href="https://www.linkedin.com/in/russell-jonathan/" target="_blank">
                            <FontAwesomeIcon :icon="faLinkedin" size="lg" />
                        </a>
                        <a href="https://github.com/russellio" target="_blank">
                            <FontAwesomeIcon :icon="faGithub" size="lg" />
                        </a>
                        <a role="button" @click="openModal('contact-modal')" class="cursor-pointer">
                            <FontAwesomeIcon :icon="faEnvelope" size="lg" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-bottom"></div>
</template>

<style scoped>
@reference "@/css/app.css";

.intro-wrapper {
    @apply w-full text-white;
    @apply bg-radial-[at_70%_50%] from-white/45 to-transparent to-65% backdrop-opacity-5;
}

.intro {
    @apply w-full;
}

.intro-backdrop {
    @apply absolute h-100 w-full bg-white opacity-20 backdrop-blur-lg backdrop-filter sm:h-1/2;
    @apply md:h-50 md:opacity-5;
}

.intro-bottom {
    @apply hidden md:block;
    @apply max-w-7xl px-4 sm:px-6 lg:px-8;
    @apply relative z-[-1] mx-auto h-3 w-9/10;
}

h1 {
    @apply font-sixtyfour text-primary;
}

h1.welcome {
    @apply text-5xl lg:text-6xl;
}

.clause-head {
    @apply text-center text-5xl text-white;
}

.name {
    @apply font-space-mono font-bold md:ps-6;
    @apply text-center tracking-tighter text-nowrap text-dark-blue uppercase;
}

p {
    @apply text-start text-2xl text-terminal-black-100;
}

strong {
    @apply font-extrabold md:px-0 md:text-[2rem] md:tracking-wider lg:px-3;
}

.profile-avatar-wrapper {
    @apply flex w-full flex-col rounded-full;
    @apply mt-[-16px] lg:max-w-[150px];
    @apply items-center md:items-end;
}

.profile-avatar-backdrop {
    @apply absolute z-30 h-57 w-55;
    @apply mt-10 lg:w-60;
    @apply md:mt-4 md:h-35 md:w-31;
    @apply rounded-sm border-2 border-secondary to-purple-100;
    @apply bg-white/20 backdrop-blur-sm backdrop-opacity-50 backdrop-saturate-100;
    @apply lg:max-h-[160px] lg:max-w-[140px];
}

.profile-avatar {
    @apply z-40 m-0 mt-[-20px] w-55 rounded-full object-cover md:me-[-5px] md:w-34;
}

.profile-pill {
    @apply w-max rounded-full border border-white bg-blue font-semibold;
    @apply my-1 px-2 py-1 text-[18px] md:text-xs lg:text-sm;
}

a svg {
    @apply p-1 text-white;
}

.profile-addon-wrapper {
    @apply grid grid-cols-1 justify-items-center md:justify-items-end;
}

.secondary-nav {
    @apply inline-flex cursor-pointer items-center justify-center rounded-lg whitespace-nowrap;
    @apply mx-2 border border-white bg-secondary text-sm;
    @apply h-10 px-5 py-3 font-space-mono font-bold uppercase;
    @apply shadow-none transition-[background-color] duration-200;
}

.secondary-nav:hover {
    @apply border-secondary bg-terminal-black-200 text-black shadow-xl shadow-black;
}

.btn-primary {
    @apply mx-4 rounded-md border-2 bg-secondary px-7 py-1 font-space-mono font-bold text-white uppercase transition-[background-color] duration-200;
}

.btn-primary:hover {
    @apply bg-primary;
}

.fallback-background {
    position: fixed;
    height: 100%;
    width: 100%;
    overflow: hidden;
    z-index: -2;
    background:
        radial-gradient(at 51% 46%, #041028 0, transparent 50%), radial-gradient(at 85% 99%, #330509 0, transparent 50%),
        radial-gradient(at 18% 22%, #111b4f 0, transparent 50%), #041028;
    transition: opacity 0.5s ease-in-out;
    opacity: 1;
}

.fallback-background.fade-out {
    opacity: 0;
}

.background-fade-enter-active {
    transition: opacity 0.5s ease-in-out;
}

.background-fade-enter-from {
    opacity: 0;
}

.background-fade-enter-to {
    opacity: 1;
}

.text-outline {
    text-shadow:
        -1px 0 1px var(--color-white),
        1px 0 1px var(--color-white),
        0 0 1px var(--color-white),
        -1px -1px 1px var(--color-white),
        1px -1px 1px var(--color-white),
        0 -1px 1px var(--color-white),
        -1px 1px 1px var(--color-white),
        1px 1px 1px var(--color-white),
        0 1px 1px var(--color-white);
}
</style>
