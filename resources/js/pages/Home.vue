<script setup lang="ts">
import FooterBC from '@/js/layout/FooterBC.vue';
import HeaderBC from '@/js/layout/HeaderBC.vue';
import AboutSectionBC from '@/js/sections/AboutSectionBC.vue';
import ExperienceSection from '@/js/sections/ExperienceSection.vue';
import ProjectsSectionBC from '@/js/sections/ProjectsSectionBC.vue';
import WritingSection from '@/js/sections/WritingSection.vue';
</script>

<template>
    <div class="group/spotlight relative">
        <div class="mx-auto min-h-screen max-w-screen-xl px-6 py-12 font-sans md:px-12 md:py-16 lg:py-0">
            <a
                href="#content"
                class="absolute top-0 left-0 block -translate-x-full rounded bg-yellow-500 px-4 py-3 text-sm font-bold tracking-widest text-slate-900 uppercase focus-visible:translate-x-0 focus-visible:text-slate-900"
                >Skip to Content</a
            >
            <div class="lg:flex lg:justify-between lg:gap-4">
                <HeaderBC />
                <main id="content" class="pt-24 lg:w-[52%] lg:py-24">
                    <AboutSectionBC />
                    <ExperienceSection />
                    <ProjectsSectionBC />
                    <WritingSection />
                    <FooterBC />
                </main>
            </div>
        </div>
    </div>
</template>
