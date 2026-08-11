<script setup lang="ts">
import type { TimelinePosition } from '@/js/types/index';
import DOMPurify from 'dompurify';
import { computed } from 'vue';

const props = defineProps<{
    position: TimelinePosition;
}>();

const sanitizeHtml = (html: string) => DOMPurify.sanitize(html);

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
</script>

<template>
    <li class="mb-12">
        <div class="group relative grid pb-1 transition-all sm:grid-cols-8 sm:gap-8 md:gap-4 lg:group-hover/list:opacity-50 lg:hover:!opacity-100">
            <div
                class="absolute -inset-x-4 -inset-y-4 z-0 hidden rounded-md transition motion-reduce:transition-none lg:-inset-x-6 lg:block lg:group-hover:bg-slate-800/50 lg:group-hover:shadow-[inset_0_1px_0_0_rgba(148,163,184,0.1)] lg:group-hover:drop-shadow-lg"
            ></div>
            <header class="z-10 mt-1 mb-2 text-xs font-semibold tracking-wide text-slate-500 uppercase sm:col-span-2">
                {{ dateRange }}
            </header>
            <div class="z-10 sm:col-span-6">
                <h3 class="leading-snug font-medium text-slate-200">
                    <a
                        v-if="position.company?.link"
                        class="group/link relative inline-flex items-baseline text-base leading-tight font-medium text-slate-200"
                        :href="position.company.link"
                        target="_blank"
                        rel="noreferrer noopener"
                        :aria-label="`${position.title} at ${position.company.name} (opens in a new tab)`"
                    >
                        <span class="absolute -inset-x-4 -inset-y-2.5 hidden rounded md:-inset-x-6 md:-inset-y-4 lg:block"></span>
                        <span>
                            {{ position.title }}<template v-if="position.company"> · {{ position.company.name }}</template>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="ml-1 inline-block h-4 w-4 shrink-0 translate-y-px transition-transform group-hover/link:translate-x-1 group-hover/link:-translate-y-1 group-focus-visible/link:translate-x-1 group-focus-visible/link:-translate-y-1 motion-reduce:transition-none"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </span>
                    </a>
                    <span v-else class="inline-flex items-baseline text-base leading-tight font-medium text-slate-200">
                        {{ position.title }}<template v-if="position.company"> · {{ position.company.name }}</template>
                    </span>
                </h3>
                <div
                    v-if="position.description"
                    class="mt-2 space-y-2 text-sm leading-normal [&_li]:ml-4 [&_li]:list-disc [&_p]:leading-normal [&_ul]:space-y-1"
                    v-html="sanitizeHtml(position.description)"
                ></div>
                <ul v-if="position.skills.length" class="mt-2 flex flex-wrap" aria-label="Skills used">
                    <li v-for="skill in position.skills" :key="skill.id" class="mt-2 mr-1.5">
                        <UBadge color="primary" variant="soft" size="sm">{{ skill.name }}</UBadge>
                    </li>
                </ul>
            </div>
        </div>
    </li>
</template>
