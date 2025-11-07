<script setup lang="ts">
import ToggleSwitch from '@/js/components/ToggleSwitch.vue';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
const { scrollToSection } = useScrollToSection();
import { useModal } from '@/js/composables/useModal';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faLinkedin, faGithub } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faObjectGroup } from '@fortawesome/free-regular-svg-icons';
import { faCertificate, faLocationDot, faUserShield } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { defineAsyncComponent, ref } from 'vue';

library.add(faLocationDot, faLinkedin, faEnvelope, faObjectGroup, faUserShield, faCertificate, faGithub);

const { openModal } = useModal();

const BackgroundStars = defineAsyncComponent(() => import('@/js/components/BackgroundStars.vue'));
const showBackground = ref(false);
</script>

<template>
    <div class="hidden md:block fixed top-0 right-[-0.5rem] lg:top-4 lg:right-10 z-[999] text-white font-space-mono text-sm">
        <ToggleSwitch label="space mode:" v-model="showBackground" />
    </div>

    <div class="fallback-background" :class="{ 'fade-out': showBackground }"></div>

    <Transition name="background-fade" appear>
        <BackgroundStars v-if="showBackground" />
    </Transition>

    <div class="intro-wrapper flex justify-center items-center px-2 md:px-6 py-6 min-h-[calc(100vh-80px)] mx-auto">
       <div class="intro-backdrop hidden"></div>

        <div class="intro-content grid grid-cols-1 max-w-[768px]">
            <div class="flex flex-col md:flex-row">

                <div class="intro text-center md:text-start">
                    <h1 class="welcome">
                        Welcome!
                    </h1>

                    <h1 class="flex flex-col md:flex-row mt-2 pb-8">
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
                <div class="flex flex-col gap-8 md:flex-row items-center justify-between max-w-[600px] mt-8 mx-auto">
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

                    <div data-orientation="vertical" role="none" class="shrink-0 w-[2px] bg-white h-5 hidden md:block"></div>

                    <div class="flex pb-10 sm:pb-0 px-5 md:px-0 gap-4 md:gap-8 text-xl">
                        <a href="https://www.linkedin.com/in/russell-jonathan/" target="_blank">
                            <FontAwesomeIcon :icon="faLinkedin" size="lg" />
                        </a>
                        <a href="https://github.com/russellio" target="_blank">
                            <FontAwesomeIcon :icon="faGithub" size="lg" />
                        </a>
                        <a type="button" @click="openModal('contact-modal')" class="cursor-pointer">
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
    @apply absolute h-100 sm:h-1/2 w-full bg-white opacity-20 backdrop-blur-lg backdrop-filter;
    @apply md:h-50 md:opacity-5;
}

.intro-bottom {
    @apply hidden md:block;
    @apply max-w-7xl px-4 sm:px-6 lg:px-8;
    @apply relative z-[-1] mx-auto h-3 w-9/10;
}


h1 {
    @apply text-primary font-sixtyfour;
}

h1.welcome {
    @apply text-5xl;
}

.clause-head {
    @apply text-5xl text-center text-white;
}

.name {
    @apply font-bold font-space-mono md:ps-6;
    @apply uppercase text-center text-dark-blue text-nowrap tracking-tighter;
}

p {
    @apply text-2xl text-terminal-black-100 text-start;
}

strong {
    @apply md:px-0 lg:px-3 md:tracking-wider font-extrabold md:text-[2rem];
}

.profile-avatar-wrapper {
    @apply flex flex-col rounded-full w-full;
    @apply lg:max-w-[150px] mt-[-16px];
    @apply items-center md:items-end;
}

.profile-avatar-backdrop {
    @apply absolute z-30 w-55 h-57;
    @apply mt-10 lg:w-60;
    @apply md:mt-4 md:w-31 md:h-35;
    @apply rounded-sm border-2 border-secondary to-purple-100;
    @apply bg-white/20 backdrop-blur-sm backdrop-opacity-50 backdrop-saturate-100;
    @apply lg:max-w-[140px] lg:max-h-[160px];
}

.profile-avatar {
    @apply z-40 m-0 mt-[-20px] md:me-[-5px] w-55 md:w-34 rounded-full object-cover;

}

.profile-pill {
    @apply w-max rounded-full border border-white bg-blue font-semibold;
    @apply my-1 py-1 px-2 text-[18px] md:text-xs;
}

a svg {
    @apply text-white p-1;
}

.profile-addon-wrapper {
    @apply grid grid-cols-1 justify-items-center md:justify-items-end;
}

.fallback-background {
    @apply fixed h-full w-full overflow-hidden;
    z-index: -2;
    background:
        radial-gradient(at 51% 46%, #041028 0, transparent 50%),
        radial-gradient(at 85% 99%, #330509 0, transparent 50%),
        radial-gradient(at 18% 22%, #111b4f 0, transparent 50%),
        #041028;
    transition: opacity 1.5s ease-in-out;
    opacity: 1;
}

.fallback-background.fade-out {
    opacity: 0;
}

.background-fade-enter-active {
    transition: opacity 1.5s ease-in-out;
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
