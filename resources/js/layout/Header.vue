<script setup lang="ts">
import Nav from '@/js/layout/Nav.vue';
import Socials from '@/js/layout/Socials.vue';
import { faCertificate, faLocationDot, faUserShield } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const credentials = [
    { icon: faLocationDot, label: 'Remote · North Carolina' },
    { icon: faCertificate, label: 'Certified ScrumMaster' },
    { icon: faUserShield, label: 'Confidential Clearance' },
];
</script>

<template>
    <header class="lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-[48%] lg:flex-col lg:justify-between lg:py-24">
        <div>
            <div class="flex items-center gap-4">
                <img
                    src="/images/profile-avatar.svg"
                    alt="Jon Russell"
                    class="h-16 w-16 shrink-0 rounded-full bg-slate-800 object-cover ring-1 ring-slate-700/60"
                />
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-200 sm:text-5xl">
                        <a href="/">Jon Russell</a>
                    </h1>
                    <h2 class="mt-1 text-lg font-medium tracking-tight text-slate-200 sm:text-xl">Senior Full Stack Engineer</h2>
                </div>
            </div>

            <p class="mt-4 max-w-xs leading-normal">I create robust, scalable solutions that power modern web and mobile applications.</p>

            <ul class="mt-4 flex flex-wrap gap-2" aria-label="Credentials">
                <li
                    v-for="credential in credentials"
                    :key="credential.label"
                    class="flex items-center gap-1.5 rounded-full bg-teal-400/10 px-3 py-1 text-xs font-medium text-teal-300"
                >
                    <FontAwesomeIcon :icon="credential.icon" class="h-3 w-3" aria-hidden="true" />
                    {{ credential.label }}
                </li>
            </ul>

            <Nav />
        </div>

        <Socials />
    </header>
</template>
