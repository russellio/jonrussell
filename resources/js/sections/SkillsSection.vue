<script setup lang="ts">
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import SectionState from '@/js/components/SectionState.vue';
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
    <section id="skills" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Engineering skills & tools">
        <SectionHeading title="Engineering Skills & Tools" />
        <SectionPanel class="px-2 pt-3 pb-5">
            <SectionState :loading="isLoading" :error="error" @retry="fetchSkills">
                <template #loading>
                    <div class="flex flex-wrap gap-2 py-8">
                        <USkeleton v-for="n in 8" :key="n" class="h-6 w-20 rounded-sm" />
                    </div>
                </template>

                <div class="space-y-8 px-4 py-8">
                    <h3 class="mb-2 font-space-mono text-lg font-semibold text-slate-200">Software Engineering</h3>
                    <div class="px-0 pb-2">
                        <ul class="flex flex-wrap justify-between gap-2" aria-label="Software engineering skills">
                            <li v-for="skill in getSkillsBySlug('software')" :key="skill.id">
                                <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                            </li>
                        </ul>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="mb-2 font-space-mono font-semibold text-slate-200">Architecture & DevOps</h3>
                            <ul class="flex flex-wrap justify-start gap-2" aria-label="Architecture and DevOps skills">
                                <li v-for="skill in getSkillsBySlug('devops')" :key="skill.id">
                                    <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="mb-2 font-space-mono font-semibold">Leadership & Team Building</h3>
                            <ul class="flex flex-wrap justify-start gap-2" aria-label="Leadership and team building skills">
                                <li v-for="skill in getSkillsBySlug('leadership')" :key="skill.id">
                                    <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="mb-2 font-space-mono font-semibold">Quality & Collaboration</h3>
                            <ul class="flex flex-wrap justify-start gap-2" aria-label="Quality and collaboration skills">
                                <li v-for="skill in getSkillsBySlug('quality')" :key="skill.id">
                                    <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="mb-2 font-space-mono text-lg font-semibold">Tools & Environment</h3>
                            <ul class="flex flex-wrap justify-start gap-2" aria-label="Tools and environment">
                                <li v-for="skill in getSkillsBySlug('tools')" :key="skill.id">
                                    <UBadge color="secondary" variant="soft" size="md" class="rounded-lg">{{ skill.name }}</UBadge>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </SectionState>
        </SectionPanel>
    </section>
</template>
