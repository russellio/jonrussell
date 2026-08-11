<script setup lang="ts">
import JobCard from '@/js/components/JobCard.vue';
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
    <section id="experience" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Work experience">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Experience</h2>
        </div>

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
    </section>
</template>
