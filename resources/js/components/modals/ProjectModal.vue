<script setup lang="ts">
import ImageModal from '@/js/components/modals/ImageModal.vue';
import { useModal } from '@/js/composables/useModal';
import type { Image, Project } from '@/js/types';
import { computed, ref, useTemplateRef } from 'vue';

const props = defineProps<{ project: Project }>();

const { isOpen, closeModal } = useModal();
const open = computed({
    get: () => isOpen('project-modal'),
    set: (value: boolean) => {
        if (!value) closeModal('project-modal');
    },
});

/** `primaryImage` leads, then the rest of the gallery, de-duplicated by `src`. */
const gallery = computed<Image[]>(() => {
    const seen = new Set<string>();

    return [props.project.primaryImage, ...(props.project.images ?? [])].filter((image): image is Image => {
        if (!image?.src || seen.has(image.src)) {
            return false;
        }

        seen.add(image.src);

        return true;
    });
});

/** Minimal structural view of what `UCarousel` exposes — the thumbnail strip only ever scrolls it. */
type CarouselRef = { emblaApi?: { scrollTo: (index: number) => void } };

const carousel = useTemplateRef<CarouselRef>('carousel');
const activeIndex = ref(0);

const goToSlide = (index: number): void => {
    activeIndex.value = index;
    carousel.value?.emblaApi?.scrollTo(index);
};

const imageModalOpen = ref(false);
const selectedImage = ref<Image | null>(null);

const zoomImage = (image: Image): void => {
    selectedImage.value = image;
    imageModalOpen.value = true;
};

const companyLogoSrc = computed(() => (props.project.company?.logo?.src ? `/images/logos/${props.project.company.logo.src}` : null));
const showCompanyName = computed(() => !companyLogoSrc.value || Boolean(props.project.company?.logo?.displayName));

const hasSidebar = computed(
    () => (props.project.technologies?.length ?? 0) > 0 || (props.project.tools?.length ?? 0) > 0 || (props.project.links?.length ?? 0) > 0,
);
</script>

<template>
    <UModal
        v-model:open="open"
        :ui="{
            content: 'max-w-4xl',
            // `shrink-0` — the header/footer are flex items of a max-height column; without it the
            // two-line title gets squeezed and bleeds over the scrolling body.
            header: 'items-start shrink-0 bg-slate-900/60',
            body: 'bg-slate-900/30',
            footer: 'justify-end shrink-0 bg-slate-900/60',
        }"
    >
        <template #title>
            <span class="block font-space-mono text-[0.65rem] tracking-[0.2em] text-slate-500 uppercase">Project</span>
            <span class="block text-lg leading-tight font-bold text-slate-100 sm:text-xl">{{ project.title }}</span>
        </template>

        <template #description>
            {{ project.byline }}
        </template>

        <template #body>
            <!-- Byline strip: who it was for, and what it won -->
            <template v-if="project.company || project.awards?.length">
                <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
                    <div v-if="project.company" class="min-w-0">
                        <span class="block font-space-mono text-[0.65rem] tracking-[0.2em] text-slate-500 uppercase">Company</span>
                        <ULink
                            v-if="project.company.link"
                            :href="project.company.link"
                            target="_blank"
                            class="mt-1.5 flex items-center gap-2.5 text-sm font-semibold text-slate-200 transition-colors hover:text-primary"
                        >
                            <img
                                v-if="companyLogoSrc"
                                :src="companyLogoSrc"
                                :alt="project.company.logo.alt || project.company.name"
                                class="h-8 w-auto shrink-0 object-contain"
                            />
                            <span :class="showCompanyName ? '' : 'sr-only'">{{ project.company.name }}</span>
                            <UIcon name="i-lucide-external-link" class="h-3.5 w-3.5 shrink-0 text-primary" />
                        </ULink>
                        <div v-else class="mt-1.5 flex items-center gap-2.5 text-sm font-semibold text-slate-200">
                            <img
                                v-if="companyLogoSrc"
                                :src="companyLogoSrc"
                                :alt="project.company.logo.alt || project.company.name"
                                class="h-8 w-auto shrink-0 object-contain"
                            />
                            <span :class="showCompanyName ? '' : 'sr-only'">{{ project.company.name }}</span>
                        </div>
                    </div>

                    <ul v-if="project.awards?.length" class="flex flex-wrap gap-1.5" aria-label="Awards">
                        <li v-for="award in project.awards" :key="award">
                            <UBadge color="secondary" variant="outline" size="sm" class="rounded-full bg-primary/30 px-2">
                                <UIcon name="i-lucide-award" class="h-4 w-4 text-gold" />
                                {{ award }}
                            </UBadge>
                        </li>
                    </ul>
                </div>

                <USeparator class="my-6" :ui="{ border: 'border-white/10' }" />
            </template>

            <!-- Gallery -->
            <section v-if="gallery.length" class="mb-8" aria-label="Project screenshots">
                <div class="relative">
                    <UCarousel
                        ref="carousel"
                        v-slot="{ item, index }"
                        :items="gallery"
                        :arrows="gallery.length > 1"
                        loop
                        :ui="{
                            item: 'basis-full',
                            prev: 'inset-s-3 sm:inset-s-3 bg-slate-950/70 text-slate-200 ring-white/15 backdrop-blur hover:bg-slate-950',
                            next: 'inset-e-3 sm:inset-e-3 bg-slate-950/70 text-slate-200 ring-white/15 backdrop-blur hover:bg-slate-950',
                        }"
                        @select="activeIndex = $event"
                    >
                        <button
                            type="button"
                            class="group relative block w-full cursor-zoom-in overflow-hidden rounded-lg border border-white/10 bg-slate-950/60"
                            :aria-label="`Enlarge image ${index + 1} of ${gallery.length}: ${item.title}`"
                            @click="zoomImage(item)"
                        >
                            <img
                                :src="`/images/projects/${item.src}`"
                                :alt="item.alt || item.title"
                                :title="item.title"
                                loading="lazy"
                                class="aspect-video max-h-[24vh] w-full object-contain transition-transform duration-300 group-hover:scale-[1.015]"
                            />
                            <span
                                class="pointer-events-none absolute inset-0 flex items-end justify-center bg-linear-to-t from-slate-950/80 to-transparent p-3 opacity-0 transition-opacity group-hover:opacity-100 group-focus-visible:opacity-100"
                            >
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-100">
                                    <UIcon name="i-lucide-maximize-2" class="h-3.5 w-3.5" />
                                    {{ item.title }}
                                </span>
                            </span>
                        </button>
                    </UCarousel>

                    <span
                        v-if="gallery.length > 1"
                        class="pointer-events-none absolute inset-e-3 top-3 rounded-full bg-slate-950/70 px-2.5 py-0.5 font-space-mono text-[0.65rem] text-slate-300 backdrop-blur"
                    >
                        {{ activeIndex + 1 }} / {{ gallery.length }}
                    </span>
                </div>

                <div v-if="gallery.length > 1" class="mt-3 flex snap-x gap-2 overflow-x-auto pb-1">
                    <button
                        v-for="(image, index) in gallery"
                        :key="image.src"
                        type="button"
                        class="thumb"
                        :class="index === activeIndex ? 'thumb-active' : ''"
                        :aria-label="`Show image ${index + 1}: ${image.title}`"
                        :aria-current="index === activeIndex"
                        @click="goToSlide(index)"
                    >
                        <img :src="`/images/projects/${image.src}`" :alt="image.alt || image.title" loading="lazy" />
                    </button>
                </div>
            </section>

            <div class="grid gap-8" :class="hasSidebar ? 'lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]' : ''">
                <div class="min-w-0">
                    <section v-if="project.keyTakeaways?.length" class="mb-8">
                        <USeparator decorative position="start" class="mb-3" :ui="{ border: 'border-white/10', container: 'me-3' }">
                            <h3 class="section-label">Key Takeaways</h3>
                        </USeparator>
                        <ul class="space-y-2.5">
                            <li v-for="takeaway in project.keyTakeaways" :key="takeaway" class="flex gap-3 text-sm leading-relaxed text-slate-300">
                                <UIcon name="i-lucide-chevron-right" class="mt-1 h-3.5 w-3.5 shrink-0 text-primary" />
                                <span>{{ takeaway }}</span>
                            </li>
                        </ul>
                    </section>

                    <section v-if="project.description">
                        <USeparator decorative position="start" class="mb-3" :ui="{ border: 'border-white/10', container: 'me-3' }">
                            <h3 class="section-label">Overview</h3>
                        </USeparator>
                        <div
                            class="space-y-3 text-sm leading-relaxed text-slate-300 [&_a]:text-primary [&_a]:underline [&_li]:ms-5 [&_li]:list-disc [&_ul]:space-y-1"
                            v-html="project.description"
                        ></div>
                    </section>
                </div>

                <aside v-if="hasSidebar" class="min-w-0 space-y-6 lg:border-s lg:border-white/10 lg:ps-8">
                    <section v-if="project.technologies?.length">
                        <USeparator decorative position="start" class="mb-3" :ui="{ border: 'border-white/10', container: 'me-3' }">
                            <h3 class="section-label">Technologies</h3>
                        </USeparator>
                        <ul class="flex flex-wrap gap-1.5" aria-label="Technologies used">
                            <li v-for="tech in project.technologies" :key="tech.name">
                                <UBadge color="secondary" variant="soft" size="md" class="gap-1.5 rounded-sm bg-white/10 font-semibold">
                                    <UIcon v-if="tech.iconType && tech.iconName" :name="`i-${tech.iconType}-${tech.iconName}`" class="h-4 w-4" />
                                    {{ tech.name }}
                                </UBadge>
                            </li>
                        </ul>
                    </section>

                    <section v-if="project.tools?.length">
                        <USeparator decorative position="start" class="mb-3" :ui="{ border: 'border-white/10', container: 'me-3' }">
                            <h3 class="section-label">Tools</h3>
                        </USeparator>
                        <ul class="flex flex-wrap gap-1.5" aria-label="Tools used">
                            <li v-for="tool in project.tools" :key="tool.name">
                                <UBadge color="secondary" variant="soft" size="md" class="gap-1.5 rounded-sm bg-white/10 font-semibold">
                                    <UIcon v-if="tool.iconType && tool.iconName" :name="`i-${tool.iconType}-${tool.iconName}`" class="h-4 w-4" />
                                    {{ tool.name }}
                                </UBadge>
                            </li>
                        </ul>
                    </section>

                    <section v-if="project.links?.length">
                        <USeparator decorative position="start" class="mb-3" :ui="{ border: 'border-white/10', container: 'me-3' }">
                            <h3 class="section-label">Links</h3>
                        </USeparator>
                        <ul class="space-y-1.5">
                            <li v-for="link in project.links" :key="link.url">
                                <ULink
                                    :href="link.url"
                                    target="_blank"
                                    class="group/link inline-flex items-center gap-1.5 text-sm text-slate-300 transition-colors hover:text-primary focus-visible:text-primary"
                                >
                                    {{ link.title }}
                                    <UIcon
                                        name="i-lucide-external-link"
                                        class="h-3.5 w-3.5 text-primary transition-transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5"
                                    />
                                </ULink>
                            </li>
                        </ul>
                    </section>
                </aside>
            </div>

            <ImageModal v-model:open="imageModalOpen" :image="selectedImage" />
        </template>

        <template #footer="{ close }">
            <UButton color="neutral" variant="outline" label="Close" @click="close" />
        </template>
    </UModal>
</template>

<style scoped>
@reference "@/css/app.css";

/* Rendered inside a decorative `USeparator`, which supplies the trailing rule. */
.section-label {
    @apply font-space-mono text-xs tracking-[0.2em] text-slate-400 uppercase;
}

.thumb {
    @apply h-14 w-20 shrink-0 snap-start overflow-hidden rounded-md border border-white/10 bg-slate-950/60 opacity-50;
    @apply transition hover:opacity-100 focus-visible:opacity-100;
}

.thumb-active {
    @apply border-primary opacity-100;
}

.thumb img {
    @apply h-full w-full object-cover;
}
</style>
