<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import { useProjectsStore } from '@/js/stores/projectsStore';
import type { TimelinePosition } from '@/js/types/index';
import { computed, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        position: TimelinePosition;
        defaultOpen?: boolean;
    }>(),
    {
        defaultOpen: false,
    },
);

const isOpen = ref(props.defaultOpen);

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

const formatMonthYear = (dateStr: string): string => {
    const [year, month] = dateStr.split('-');
    const monthIndex = Number(month) - 1;
    return `${MONTHS[monthIndex] ?? ''} ${year}`.trim();
};

const dateRange = computed(() => {
    const start = formatMonthYear(props.position.startDate);
    const end = props.position.isCurrent || !props.position.endDate ? 'Present' : formatMonthYear(props.position.endDate);
    return `${start} — ${end}`;
});

const companyLogoSrc = computed(() => {
    return props.position.company?.logo?.src ? `/images/logos/${props.position.company.logo.src}` : null;
});

const showCompanyName = computed(() => {
    return !companyLogoSrc.value || Boolean(props.position.company?.logo?.displayName);
});

const projectsStore = useProjectsStore();
const { openModal } = useModal();

const PROJECT_LINK_PREFIX = '#project:';

function onDescriptionClick(event: MouseEvent): void {
    const anchor = (event.target as HTMLElement).closest<HTMLAnchorElement>('a');
    if (!anchor) return;
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (!anchor.hash.startsWith(PROJECT_LINK_PREFIX)) return;

    event.preventDefault();
    const slug = decodeURIComponent(anchor.hash.slice(PROJECT_LINK_PREFIX.length));
    if (projectsStore.selectProject(slug)) {
        openModal('project-modal');
    }
}
</script>

<template>
    <li class="mb-12">
        <div class="group relative pb-8 transition-all lg:group-hover/list:opacity-50 lg:hover:opacity-100!">
            <div
                class="absolute -inset-y-4 -inset-s-4 -inset-e-2 z-0 hidden rounded-md border-white/0 transition motion-reduce:transition-none lg:block lg:group-hover:border lg:group-hover:border-white/25 lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <div
                class="grid overflow-hidden transition-[max-height] duration-300 ease-in-out sm:grid-cols-8 sm:gap-8 md:gap-4"
                :class="isOpen ? 'max-h-[2000px]' : 'max-h-[260px] sm:max-h-[150px]'"
            >
                <header class="z-10 mt-1 mb-2 text-center text-xs tracking-wide text-slate-500 sm:col-span-2">
                    <div class="text-center font-semibold text-slate-400 uppercase">{{ dateRange }}</div>
                    <div v-if="position.company" class="my-6">
                        <img
                            v-if="companyLogoSrc"
                            :src="companyLogoSrc"
                            :alt="position.company.logo.alt || position.company.name"
                            class="mx-auto h-14 rounded-sm object-contain md:h-8"
                        />
                        <div v-if="showCompanyName" class="mt-2 text-center font-sans">{{ position.company.name }}</div>
                        <div v-if="position.company.description" class="mt-4 font-sans text-xs">{{ position.company.description }}</div>
                    </div>
                </header>
                <div class="z-10 sm:col-span-6">
                    <h3 class="flex flex-row gap-0.5 leading-snug font-medium text-slate-200">
                        <span class="items-baseline text-base leading-tight font-bold">
                            {{ position.title }}
                        </span>
                        <component
                            :is="position.company.link ? 'a' : 'span'"
                            v-if="position.company"
                            class="group/link ml-auto shrink"
                            v-bind="
                                position.company.link
                                    ? {
                                          href: position.company.link,
                                          target: '_blank',
                                          rel: 'noreferrer noopener',
                                          'aria-label': `${position.title} at ${position.company.name} (opens in a new tab)`,
                                      }
                                    : {}
                            "
                        >
                            <div class="flex flex-row items-center text-sm">
                                <div class="me-1.5 grow text-nowrap">{{ position.company.name }}</div>
                                <UIcon v-if="position.company.link" name="i-lucide-external-link" color="primary" />
                            </div>
                        </component>
                    </h3>
                    <div
                        v-if="position.description"
                        class="mt-2 space-y-2 text-sm leading-normal [&_a]:text-primary [&_a]:underline [&_li]:ml-4 [&_li]:list-disc [&_p]:leading-normal [&_ul]:space-y-1"
                        v-html="position.description"
                        @click="onDescriptionClick"
                    ></div>
                    <ul v-if="position.skills.length" class="mt-2 flex flex-wrap" aria-label="Skills used">
                        <li v-for="skill in position.skills" :key="skill.id" class="me-1.5 mt-2">
                            <UBadge color="secondary" variant="soft" size="sm" class="rounded-sm bg-white/10 font-semibold">{{ skill.name }}</UBadge>
                        </li>
                    </ul>
                </div>
            </div>

            <!--            <UCollapsible v-model:open="isOpen" class="absolute inset-x-0 bottom-0 z-20">-->
            <!--                <template #default>-->
            <!--                    <button-->
            <!--                        type="button"-->
            <!--                        class="w-full cursor-pointer bg-gradient-to-t from-slate-900 via-slate-900/90 to-transparent pt-6 pb-1 text-center text-xs font-semibold tracking-wide text-teal-400 uppercase hover:text-teal-300"-->
            <!--                    >-->
            <!--                        {{ isOpen ? 'Show less' : 'Show more' }}-->
            <!--                    </button>-->
            <!--                </template>-->
            <!--            </UCollapsible>-->
        </div>
    </li>
</template>
