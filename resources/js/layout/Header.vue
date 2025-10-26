<script setup lang="ts" xmlns:md="http://www.w3.org/1999/html">
import Nav from '@/js/layout/Nav.vue';
import { useModal } from '@/js/composables/useModal';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faLinkedin, faGithub } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faObjectGroup } from '@fortawesome/free-regular-svg-icons';
import { faCertificate, faLocationDot, faUserShield } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { defineAsyncComponent, onMounted, ref } from 'vue';

library.add(faLocationDot, faLinkedin, faEnvelope, faObjectGroup, faUserShield, faCertificate, faGithub);

const { openModal } = useModal();

const BackgroundStars = defineAsyncComponent(() => import('@/js/components/BackgroundStars.vue'));
const backgroundLoaded = ref(false);
const showBackground = ref(false);
const handleBackgroundReady = () => {
    backgroundLoaded.value = true;
    // Small delay to ensure smooth transition
    setTimeout(() => {
        showBackground.value = true;
    }, 100);
};

onMounted(() => {
    setTimeout(() => {
        // showBackground.value = true;
    }, 50);
});

const scrollToSection = (sectionName: string) => {
    console.log('scrollToSection() - ', sectionName);

    // Use the global scrollToSection function if available
    if (typeof window !== 'undefined' && window.scrollToSection) {
        window.scrollToSection(sectionName);
    } else {
        // Fallback: try to find the section by ID
        const element = document.getElementById(sectionName);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
};
</script>

<template>
    <div class="fallback-background" :class="{ 'fade-out': showBackground }"></div>

    <Transition name="background-fade" appear>
<!--        <BackgroundStars v-if="showBackground" ref="stars" @backgroundReady="handleBackgroundReady" />-->
        <BackgroundStars v-if="showBackground" ref="stars" />
    </Transition>

    <div class="intro-wrapper flex justify-center items-center p-6 min-h-[calc(100vh-80px)] mx-auto">
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

                    <p class="me-20">
                        A <strong>full-stack software engineer</strong>
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
                            ScrumMaster Certification
                        </div>
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faUserShield" size="sm" class="me-1" />
                            Confidential Security Clearance
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <div class="flex flex-col gap-8 lg:gap-0 lg:flex-row lg:items-center justify-between max-w-[600px] mt-8 mx-auto">
                    <div class="flex gap-2 lg:gap-4">
                        <button class="secondary-nav"
                                @click="openModal('contact-modal')">
                            <FontAwesomeIcon :icon="faEnvelope" size="lg" class="me-3" />
                            Contact Me
                        </button>
                        <button class="secondary-nav"
                                @click="() => scrollToSection('projects')">
                            <FontAwesomeIcon :icon="faObjectGroup" size="lg" class="me-3" />
                            View Projects
                        </button>
                    </div>

                    <div data-orientation="vertical" role="none" class="shrink-0 w-[2px] bg-white h-5 hidden md:block"></div>

                    <div class="flex items-center pb-10 sm:pb-0 px-5 md:px-0 gap-14 md:gap-8 text-xl">
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

.intro-content {
    /*@apply mx-auto max-w-[768px];*/
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
    /*@apply bg-gradient-to-b from-transparent to-dark-blue;*/
}

/*div.welcome {
    !*@apply w-full mt-40 max-w-7xl lg:w-3/4 mx-auto;*!
    @apply px-2 lg:px-10;
}*/

h1 {
    @apply /*absolute top-20*/ text-primary font-sixtyfour /*md:text-6xl lg:text-7xl*/;
    /*@apply w-full md:w-3/4 md:text-nowrap md:-indent-10;*/
}

h1.welcome {
    /*@apply block*/ /*md:text-6xl lg:text-5xl*/;
    @apply text-5xl;
}

.clause-head {
    @apply text-5xl text-center text-white;
    /*@apply text-white md:text-6xl lg:text-7xl align-top block   mt-4 md:text-start;*/
}

.name {
    @apply font-bold font-space-mono ps-6 /*w-full md:w-auto lg:w-4/5 mx-auto*/ /*align-text-bottom*/;
    @apply uppercase text-center text-dark-blue text-nowrap tracking-tighter;
    /*@apply text-6xl md:text-7xl lg:text-5xl;*/
}

p {
    /*@apply ms-4 md:ms-0 mt-10 md:mt-14 lg:mt-40 mb-8;*/
    /*@apply w-full md:w-5/6 lg:w-[93%] md:mx-auto md:-indent-8;*/
    @apply text-xl lg:text-2xl text-terminal-black-100 text-start;
    /*@apply md:leading-relaxed;*/
}

strong {
    @apply /*px-3 text-3xl lg:text-4xl lg:tracking-wider*/ font-extrabold;
}

.profile-avatar-wrapper {
    @apply flex flex-col rounded-full w-full;
    @apply lg:max-w-[132px] mt-[-16px];
/*    @apply md:h-80 md:w-73;
    @apply lg:h-90 lg:w-84;*/
    @apply items-center lg:items-end;
}

.profile-avatar-backdrop {
    @apply absolute z-30 w-40 h-50;
    @apply lg:max-w-[132px] lg:max-h-[140px];
    /*@apply md:w-52 md:h-50;*/
    @apply mt-8;
/*    @apply md:me-4 md:mt-16;
    @apply lg:me-2 lg:mt-18;*/
    @apply rounded-sm border-2 border-secondary to-purple-100;
    @apply bg-white/20 backdrop-blur-sm backdrop-opacity-50 backdrop-saturate-100;
}

.profile-avatar {
    @apply z-40 m-0 w-34 /*w-76 md:h-full md:w-full lg:w-60*/ rounded-full object-cover;
    /*width:180px;*/
}

.profile-pill {
    @apply w-max rounded-full border-1 border-white bg-blue font-semibold;
    @apply my-1 p-0.5 px-2 text-[18px] md:text-xs;
}

.profile-icons {
    @apply z-50 text-right pe-4;
}

a svg {
    @apply text-white p-1;
}

.profile-addon-wrapper {
    @apply grid grid-cols-1 justify-items-center md:justify-items-end;
}

/* Fallback background for immediate visibility */
.fallback-background {
    @apply fixed h-full w-full overflow-hidden;
    z-index: -2;
    background:
        radial-gradient(at 51% 46%, #041028 0, transparent 50%), radial-gradient(at 85% 99%, #330509 0, transparent 50%),
        radial-gradient(at 18% 22%, #111b4f 0, transparent 50%), #041028;
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
