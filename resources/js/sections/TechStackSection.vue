<script setup lang="ts">
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import type { TechStackItem } from '@/js/types/index';

defineProps<{
    techStack: TechStackItem[];
}>();
</script>

<template>
    <section id="tech-stack" aria-label="Primary tech stack" class="mb-16 scroll-mt-16 md:mb-24 lg:mt-0 lg:mb-20 lg:scroll-mt-20 lg:pt-0">
        <SectionHeading title="Tech Stack" />

        <SectionPanel>
            <div class="flex flex-wrap justify-center gap-6 py-4">
                <div v-for="item in techStack" :key="item.tech" class="flex items-center gap-2">
                    <UIcon
                        v-if="item.iconType && item.iconName"
                        :name="`i-${item.iconType}-${item.iconName}`"
                        class="h-4 w-4 shrink-0 text-slate-300"
                    />
                    <span class="align-middle text-sm text-slate-400">
                        {{ item.tech }}
                    </span>
                </div>
            </div>
        </SectionPanel>
    </section>
</template>
