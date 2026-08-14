<script setup lang="ts">
import JobCard from '@/js/components/JobCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import type { TimelinePosition } from '@/js/types/index';

defineProps<{
    positions: TimelinePosition[];
}>();
</script>

<template>
    <section id="experience" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Work experience">
        <SectionHeading title="Professional Experience" />
        <SectionPanel class="ps-2 pt-8">
            <div>
                <ol class="group/list">
                    <JobCard v-for="position in positions" :key="position.id" :position="position" />
                </ol>
                <!-- TODO: re-enable when public/resume.pdf is supplied -->
            </div>
        </SectionPanel>
    </section>
</template>
