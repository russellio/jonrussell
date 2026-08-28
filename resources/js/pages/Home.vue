<script setup lang="ts">
import ContactModal from '@/js/components/modals/ContactModal.vue';
import { useModal } from '@/js/composables/useModal';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import Footer from '@/js/layout/Footer.vue';
import Header from '@/js/layout/Header.vue';
import SpaceMode from '@/js/layout/SpaceMode.vue';
import AboutSection from '@/js/sections/AboutSection.vue';
import ExperienceSection from '@/js/sections/ExperienceSection.vue';
import PostsSection from '@/js/sections/PostsSection.vue';
import ProjectsSection from '@/js/sections/ProjectsSection.vue';
import SkillsSection from '@/js/sections/SkillsSection.vue';
import TechStackSection from '@/js/sections/TechStackSection.vue';
import type { AppPageProps, Post, Project, SkillType, TechStackItem, TimelinePosition } from '@/js/types/index';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, watch } from 'vue';

type PageProps = AppPageProps<{ scrollTo?: string | null }>;

interface PageMeta {
    title: string;
    description: string;
    canonical: string;
}

defineProps<{
    meta: PageMeta;
    techStack: TechStackItem[];
    skillTypes: SkillType[];
    positions: TimelinePosition[];
    projects: Project[];
    posts: Post[];
}>();

const page = usePage<PageProps>();
const { isOpen, openModal } = useModal();
const { scrollToSection } = useScrollToSection();

const isContactOpen = computed(() => isOpen('contact-modal'));

const performScrollAction = async (scrollTo: string | undefined | null) => {
    if (!scrollTo) return;

    if (scrollTo === 'contact') {
        openModal('contact-modal');
        return;
    }

    await nextTick();
    scrollToSection(scrollTo);
};

onMounted(async () => {
    await performScrollAction(page.props.scrollTo);
});

watch(
    () => page.props.scrollTo,
    async (newScrollTo) => {
        await performScrollAction(newScrollTo);
    },
);
</script>

<template>
    <div>
        <Head>
            <title>{{ meta.title }}</title>
            <meta name="description" :content="meta.description" head-key="description" />
            <link rel="canonical" :href="meta.canonical" head-key="canonical" />
        </Head>

        <SpaceMode />

        <div class="relative z-10 mx-auto min-h-screen max-w-7xl px-6 py-12 font-sans md:px-8 md:py-8 md:pt-0 lg:py-0">
            <!-- <a
                href="#content"
                class="absolute top-0 left-0 block -translate-x-full rounded bg-yellow-500 px-4 py-3 text-sm font-bold tracking-widest text-slate-900 uppercase focus-visible:translate-x-0 focus-visible:text-slate-900"
                >Skip to Content</a
            > -->
            <div class="lg:flex lg:justify-between lg:gap-4">
                <Header />
                <main id="content" class="pt-12 lg:w-[68%] lg:py-14 lg:pt-24">
                    <div>
                        <AboutSection />
                        <TechStackSection :tech-stack="techStack" />
                        <SkillsSection :skill-types="skillTypes" />
                        <ExperienceSection :positions="positions" />
                        <ProjectsSection :projects="projects" />
                        <PostsSection :posts="posts" />
                    </div>
                    <Footer />
                </main>
            </div>
        </div>

        <ContactModal v-if="isContactOpen" />
    </div>
</template>
