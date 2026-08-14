<script setup lang="ts">
import type { TimelinePosition } from '@/js/types/index';
import { computed } from 'vue';

const props = defineProps<{
    position: TimelinePosition;
}>();

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
</script>

<template>
    <li class="mb-12">
        <div class="group relative grid pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:group-hover/list:opacity-50 lg:hover:opacity-100!">
            <div
                class="absolute -inset-y-4 -inset-s-4 -inset-e-2 z-0 hidden rounded-md border-white/0 transition motion-reduce:transition-none lg:block lg:group-hover:border lg:group-hover:border-white/25 lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <header class="z-10 mt-1 mb-2 text-center text-xs tracking-wide text-slate-500 sm:col-span-2">
                <div class="text-center font-semibold text-slate-400 uppercase">{{ dateRange }}</div>
                <div v-if="position.company" class="mt-6">
                    <img
                        v-if="companyLogoSrc"
                        :src="companyLogoSrc"
                        :alt="position.company.logo.alt || position.company.name"
                        class="mx-auto h-8 rounded-sm object-contain"
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
                        class="group/link ms-auto"
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
                        <div class="flex flex-row text-sm">
                            <div class="me-1.5 grow text-nowrap">{{ position.company.name }}</div>
                            <UIcon v-if="position.company.link" name="i-lucide-external-link" color="secondary" />
                        </div>
                    </component>
                </h3>
                <div
                    v-if="position.description"
                    class="mt-2 space-y-2 text-sm leading-normal [&_li]:ml-4 [&_li]:list-disc [&_p]:leading-normal [&_ul]:space-y-1"
                    v-html="position.description"
                ></div>
                <ul v-if="position.skills.length" class="mt-2 flex flex-wrap" aria-label="Skills used">
                    <li v-for="skill in position.skills" :key="skill.id" class="me-1.5 mt-2">
                        <UBadge color="secondary" variant="soft" size="sm" class="rounded-sm bg-white/10 font-semibold">{{ skill.name }}</UBadge>
                    </li>
                </ul>
            </div>
        </div>
    </li>
</template>
