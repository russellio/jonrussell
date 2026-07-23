<script setup lang="ts">
import { get } from '@/js/lib/api';
import type { ApiResponse, Skill, SkillType } from '@/js/types/index';
import { onMounted, ref } from 'vue';

const skillTypes = ref<SkillType[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);

const getSkillsBySlug = (slug: string): Skill[] => {
    const skillType = skillTypes.value.find((st) => st.slug === slug);
    return skillType?.skills ?? [];
};

const fetchSkills = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        const { data } = await get<ApiResponse<SkillType[]>>('/api/skills');
        skillTypes.value = data;
    } catch (err) {
        console.error('Error fetching skills:', err);
        error.value = err instanceof Error ? err.message : 'Failed to load skills';
        skillTypes.value = [];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchSkills();
});
</script>

<template>
    <section id="skills" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="Skills and tools">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">Skills & Tools</h2>
        </div>

        <div v-if="isLoading" class="py-8 text-sm text-slate-500">Loading skills…</div>

        <div v-else-if="error" class="py-8 text-sm text-slate-500">
            <p>{{ error }}</p>
            <button
                type="button"
                class="mt-3 rounded border border-slate-700 px-3 py-1.5 text-xs font-medium text-slate-300 transition-colors hover:border-teal-300/50 hover:text-teal-300 motion-reduce:transition-none"
                @click="fetchSkills"
            >
                Retry
            </button>
        </div>

        <div v-else class="space-y-8">
            <div>
                <h3 class="mb-3 text-sm font-semibold text-slate-200">Software Engineering</h3>
                <ul class="flex flex-wrap gap-2" aria-label="Software engineering skills">
                    <li v-for="skill in getSkillsBySlug('software')" :key="skill.id">
                        <span class="inline-flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs font-medium text-teal-300">
                            {{ skill.name }}
                        </span>
                    </li>
                </ul>
            </div>

            <div class="grid gap-8 sm:grid-cols-3">
                <div>
                    <h3 class="mb-3 text-sm font-semibold text-slate-200">Architecture & DevOps</h3>
                    <ul class="flex flex-wrap gap-2" aria-label="Architecture and DevOps skills">
                        <li v-for="skill in getSkillsBySlug('devops')" :key="skill.id">
                            <span class="inline-flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs font-medium text-teal-300">
                                {{ skill.name }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-semibold text-slate-200">Quality & Collaboration</h3>
                    <ul class="flex flex-wrap gap-2" aria-label="Quality and collaboration skills">
                        <li v-for="skill in getSkillsBySlug('quality')" :key="skill.id">
                            <span class="inline-flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs font-medium text-teal-300">
                                {{ skill.name }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-semibold text-slate-200">Leadership & Team Building</h3>
                    <ul class="flex flex-wrap gap-2" aria-label="Leadership and team building skills">
                        <li v-for="skill in getSkillsBySlug('leadership')" :key="skill.id">
                            <span class="inline-flex items-center rounded-full bg-teal-400/10 px-3 py-1 text-xs font-medium text-teal-300">
                                {{ skill.name }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
