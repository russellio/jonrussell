<script setup lang="ts" xmlns:md="http://www.w3.org/1999/html">
import Nav from '@/js/layout/Nav.vue';
import { useModal } from '@/js/composables/useModal';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope } from '@fortawesome/free-regular-svg-icons';
import { faCertificate, faLocationDot, faUserShield } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { defineAsyncComponent, onMounted, ref } from 'vue';

library.add(faLocationDot, faLinkedin, faEnvelope, faUserShield, faCertificate);

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
        showBackground.value = true;
    }, 50);
});
</script>

<template>
    <div class="fallback-background" :class="{ 'fade-out': showBackground }"></div>

    <Transition name="background-fade" appear>
        <BackgroundStars v-if="showBackground" ref="stars" @backgroundReady="handleBackgroundReady" />
    </Transition>

    <div class="intro-wrapper">
        <div class="intro-backdrop"></div>
        <div class="intro-content">
            <div class="intro">
                <div class="welcome">
                    <h1>
                        <span class="welcome">Welcome!</span>
                        <span class="clause-head">I'm</span>
                        <span class="name text-outline">Jon Russell</span>
                    </h1>
                    <p>
                        A <strong>full-stack software engineer</strong>
                        who creates robust, scalable solutions that power modern web applications.
                    </p>
                </div>

                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-backdrop"></div>
                    <img src="/images/profile-avatar.svg" alt="Jon Russell" class="profile-avatar" />
                    <div class="profile-addon-wrapper">
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faLocationDot" class="text-gold" size="sm" />
                            Remote - North Carolina
                        </div>
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faCertificate" class="text-gold" size="sm" />
                            ScrumMaster Certification
                        </div>
                        <div class="profile-pill">
                            <FontAwesomeIcon :icon="faUserShield" class="text-gold" size="sm" />
                            DoD Confidential Security Clearance
                        </div>
                        <div class="profile-icons">
                            <a href="https://www.linkedin.com/in/russell-jonathan/" target="_blank">
                                <FontAwesomeIcon :icon="faLinkedin" size="xl" />
                            </a>
                            <a type="button" data-dialog-target="contact-modal" @click="openModal('contact-modal')" class="cursor-pointer">
                                <FontAwesomeIcon icon="fa-regular fa-envelope" size="lg" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Nav ref="navigation" />

    <div class="intro-bottom"></div>
</template>

<style scoped>
@reference "@/css/app.css";

.intro-wrapper {
    @apply w-full text-white;
    @apply bg-radial-[at_70%_50%] from-white/45 to-transparent to-65% backdrop-opacity-5;
}

.intro-content {
    @apply max-w-5xl mx-auto;
}

.intro {
    @apply mb-2 pt-20 pb-6 w-full;
    @apply flex flex-col md:flex-row;
    @apply text-center lg:text-start;
}

.intro-backdrop {
    @apply absolute mt-12 h-100 sm:h-1/2 w-full bg-white opacity-20 backdrop-blur-lg backdrop-filter;
    @apply md:h-full md:opacity-5;
}

.intro-bottom {
    @apply hidden md:block;
    @apply max-w-7xl px-4 sm:px-6 lg:px-8;
    @apply relative z-[-1] clear-right mx-auto h-3 w-9/10;
    /*@apply bg-gradient-to-b from-transparent to-dark-blue;*/
}

div.welcome {
    @apply w-full mt-40 max-w-7xl lg:w-3/4 mx-auto;
    @apply px-2 lg:px-10;
}

h1 {
    @apply flex flex-col align-top;
    @apply mt-20 text-primary font-sixtyfour text-5xl md:text-6xl lg:text-7xl;
    @apply w-full md:w-3/4 md:text-nowrap md:-indent-10;
}

span.welcome {
    @apply inline-block md:text-6xl lg:text-5xl;
}

.clause-head {
    @apply text-white md:text-6xl lg:text-5xl align-top inline-block md:inline pt-4 md:text-start;
}

.name {
    @apply font-bold font-space-mono align-text-bottom;
    @apply uppercase text-center lg:text-start text-dark-blue text-nowrap tracking-tighter;
    @apply inline-block text-6xl md:text-7xl lg:text-6xl;
}

p {
    @apply ms-4 md:ms-0 mt-10 md:mt-14 lg:mt-40 mb-8;
    @apply w-full md:w-5/6 lg:w-[93%] md:mx-auto md:-indent-8;
    @apply text-2xl text-terminal-black-50 text-left;
    @apply md:leading-relaxed lg:text-3xl;
}

strong {
    @apply px-3 text-3xl lg:text-4xl font-bold lg:tracking-wider;
}

.profile-avatar-wrapper {
    @apply flex flex-col justify-start rounded-full md:float-right;
    @apply md:h-80 md:w-73;
    @apply lg:h-90 lg:w-84;
    @apply items-center lg:items-end;
}

.profile-avatar-backdrop {
    @apply absolute z-30 w-74 h-70;
    @apply md:w-52 md:h-50;
    @apply lg:w-56 lg:h-60;
    @apply ms-3 mt-24;
    @apply md:me-4 md:mt-16;
    @apply lg:me-2 lg:mt-18;
    @apply rounded-sm border-2 border-secondary to-purple-100;
    @apply bg-white/20 backdrop-blur-sm backdrop-opacity-50 backdrop-saturate-100;
}

.profile-avatar {
    @apply z-40 m-1 lg:me-0 w-76 md:h-full md:w-full lg:w-60 rounded-full object-cover md:mb-2;
}

.profile-pill {
    @apply w-max rounded-full border-1 border-white bg-blue font-bold;
    @apply my-2 p-1 px-4 text-[18px] md:text-sm;
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
</style>
