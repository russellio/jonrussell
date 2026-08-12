<script setup lang="ts">
import JobCard from '@/js/components/JobCard.vue';
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionState from '@/js/components/SectionState.vue';
import { get } from '@/js/lib/api';
import type { ApiResponse, TimelinePosition } from '@/js/types/index';
import { computed, onMounted, ref } from 'vue';

const positions = ref<TimelinePosition[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const curatedPositions = computed(() => positions.value.filter((position) => Boolean(position.description)));

const fetchTimeline = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<TimelinePosition[]>>('/api/timeline');
        positions.value = data;
    } catch (err) {
        console.error('Error fetching timeline:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load experience';
        positions.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchTimeline();
});
</script>

<template>
    <section id="experience" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Work experience">
        <SectionHeading title="Experience" />
        <div class="rounded-br-2xl rounded-bl-2xl border-b border-brand-red bg-black/30 px-2 ps-4 pt-3">
            <SectionState :loading="isLoading" :error="error" @retry="fetchTimeline">
                <template #loading>
                    <div class="space-y-6 py-8">
                        <div v-for="n in 3" :key="n" class="grid gap-4 sm:grid-cols-8 sm:gap-8">
                            <USkeleton class="h-4 w-20 sm:col-span-2" />
                            <div class="sm:col-span-6">
                                <USkeleton class="h-5 w-1/2" />
                                <USkeleton class="mt-2 h-4 w-full" />
                            </div>
                        </div>
                    </div>
                </template>

                <div>
                    <ol class="group/list">
                        <JobCard v-for="position in curatedPositions" :key="position.id" :position="position" />
                    </ol>
                    <!-- TODO: re-enable when public/resume.pdf is supplied -->
                </div>
            </SectionState>
        </div>
    </section>
</template>
