<script setup lang="ts">
import Nav from '@/js/layout/Nav.vue';
import Socials from '@/js/layout/Socials.vue';

const credentials = [
    { icon: 'map-pin', label: 'Remote · North Carolina' },
    // { icon: 'id-badge', label: 'Certified ScrumMaster' },
    { icon: 'user-shield', label: 'Confidential Clearance' },
];
</script>

<template>
    <header class="lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-100 lg:max-w-100 lg:flex-col lg:justify-between lg:py-16">
        <div class="flex flex-col items-center lg:items-start">
            <div class="flex items-center gap-4">
                <div class="absolute z-30 mt-1 h-22 w-20 rounded-lg border-2 border-brand-red bg-white/30"></div>
                <img src="/images/profile-avatar.svg" alt="Jon Russell" class="z-40 w-20 shrink-0 object-cover" />
                <div>
                    <h1 class="pt-2 text-4xl font-bold tracking-tight text-slate-100 sm:text-5xl">
                        <a href="/">Jon Russell</a>
                    </h1>
                    <h2 class="mt-1 text-lg font-medium tracking-tight text-slate-100 sm:text-xl lg:ps-1">Senior Full Stack Engineer</h2>
                </div>
            </div>

            <p class="mt-4 max-w-xs leading-normal text-slate-200">
                Building robust, scalable solutions for modern web and mobile applications today.
                <br />
                Saving the world tomorrow.
            </p>

            <ul class="mt-4 flex gap-2" aria-label="Credentials">
                <li
                    v-for="credential in credentials"
                    :key="credential.label"
                    class="flex items-center gap-1.5 rounded-full border border-slate-300 bg-blue px-3 py-1 text-xs font-semibold text-white"
                >
                    <UIcon :name="`i-lucide-${credential.icon}`" class="-ms-1 h-3 w-3 border-e border-e-white/30 pe-1" aria-hidden="true" />
                    {{ credential.label }}
                </li>
            </ul>

            <Socials />

            <Nav />
        </div>
    </header>
</template>
