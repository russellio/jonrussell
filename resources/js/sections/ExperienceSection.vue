<script setup lang="ts">
import JobCard from '@/js/components/JobCard.vue';
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

        <div v-if="isLoading" class="py-8 text-sm text-slate-500">Loading experience…</div>

        <div v-else-if="error" class="py-8 text-sm text-slate-500">
            <p>{{ error }}</p>
            <button
                type="button"
                class="mt-3 rounded border border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-300 transition-colors hover:border-teal-300/50 hover:text-teal-300 motion-reduce:transition-none"
                @click="fetchTimeline"
            >
                Retry
            </button>
        </div>

        <div v-else>
            <ol class="group/list">
                <JobCard v-for="position in curatedPositions" :key="position.id" :position="position" />
            </ol>
            <!-- TODO: re-enable when public/resume.pdf is supplied -->
        </div>
    </section>
</template>
