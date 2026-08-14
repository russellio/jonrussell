<script setup lang="ts">
import Nav from '@/js/layout/Nav.vue';
import Socials from '@/js/layout/Socials.vue';

const credentials = [
    { icon: 'map-pin', label: 'Remote · North Carolina' },
    // { icon: 'id-badge', label: 'Certified ScrumMaster' },
    { icon: 'user-shield', label: 'DoD Confidential Security clearance' },
];
</script>

<template>
    <header class="lg:sticky lg:top-0 lg:flex lg:max-h-screen lg:w-100 lg:max-w-100 lg:flex-col lg:justify-between lg:py-4">
        <div class="flex flex-col items-center lg:items-start">
            <div class="flex items-center gap-4">
                <div class="absolute z-30 mt-1 h-22 w-20 rounded-lg border-2 border-brand-red bg-white/30"></div>
                <img src="/images/profile-avatar.svg" alt="Jon Russell" class="z-40 aspect-[1021/1501] w-20 shrink-0 object-cover" />
                <div>
                    <h1 class="pt-2 text-4xl font-bold tracking-tight text-slate-100 sm:text-5xl">
                        <a href="/">Jon Russell</a>
                    </h1>
                    <h2 class="mt-1 text-xl font-medium tracking-tight text-slate-100 lg:ps-1">Senior Full Stack Engineer</h2>
                </div>
            </div>

            <div class=""></div>
            <p class="mt-4 max-w-xs leading-normal text-slate-200">
                Building robust, scalable solutions for modern web and mobile applications today.
            </p>
            <p class="my-2 max-w-xs leading-normal text-slate-200">Saving the world tomorrow.</p>

            <ul class="mt-6 flex flex-col gap-3 sm:flex-row lg:flex-col" aria-label="Credentials">
                <li v-for="credential in credentials" :key="credential.label">
                    <UBadge
                        :leadingIcon="`i-lucide-${credential.icon}`"
                        size="md"
                        color="secondary"
                        variant="outline"
                        class="bg-primary px-3 py-1 text-xs font-semibold text-white"
                        aria-hidden="true"
                    >
                        <span class="ms-1 border-s border-white/25 ps-1.5">{{ credential.label }}</span>
                    </UBadge>
                </li>
            </ul>

            <Socials />

            <Nav />
        </div>
    </header>
</template>
