<script setup lang="ts">
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import SectionState from '@/js/components/SectionState.vue';
import { get } from '@/js/lib/api';
import type { ApiResponse, TechStackItem } from '@/js/types/index';
import { onMounted, ref } from 'vue';

const techStack = ref<TechStackItem[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const fetchTechStack = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<TechStackItem[]>>('/api/tech-stack');
        techStack.value = data;
    } catch (err) {
        console.error('Error fetching tech stack:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load tech stack';
        techStack.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchTechStack();
});
</script>

<template>
    <section id="tech-stack" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-36" aria-label="Primary tech stack">
        <SectionHeading title="Primary Tech Stack" />

        <SectionPanel>
            <SectionState :loading="isLoading" :error="error" @retry="fetchTechStack">
                <template #loading>
                    <div class="flex flex-wrap justify-center gap-6 py-4">
                        <USkeleton v-for="n in 10" :key="n" class="h-4 w-20" />
                    </div>
                </template>

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
            </SectionState>
        </SectionPanel>
    </section>
</template>
