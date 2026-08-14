<script setup lang="ts">
import SectionHeading from '@/js/components/SectionHeading.vue';
import SectionPanel from '@/js/components/SectionPanel.vue';
import type { Skill, SkillType } from '@/js/types/index';

const props = defineProps<{
    skillTypes: SkillType[];
}>();

const getSkillsBySlug = (slug: string): Skill[] => {
    const skillType = props.skillTypes.find((st) => st.slug === slug);
    return skillType?.skills ?? [];
};
</script>

<template>
    <section id="skills" class="mb-16 scroll-mt-16 md:mb-24 lg:scroll-mt-24" aria-label="Engineering skills & tools">
        <SectionHeading title="Engineering Skills & Tools" />
        <SectionPanel class="px-2 pb-5 pt-3">
            <div class="space-y-8 px-4 py-8">
                <h3 class="font-space-mono mb-2 text-lg font-semibold text-slate-200">Software Engineering</h3>
                <div class="px-0 pb-2">
                    <ul class="flex flex-wrap justify-between gap-2" aria-label="Software engineering skills">
                        <li v-for="skill in getSkillsBySlug('software')" :key="skill.id">
                            <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                        </li>
                    </ul>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-space-mono mb-2 font-semibold text-slate-200">Architecture & DevOps</h3>
                        <ul class="flex flex-wrap justify-start gap-2" aria-label="Architecture and DevOps skills">
                            <li v-for="skill in getSkillsBySlug('devops')" :key="skill.id">
                                <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-space-mono mb-2 font-semibold">Leadership & Team Building</h3>
                        <ul class="flex flex-wrap justify-start gap-2" aria-label="Leadership and team building skills">
                            <li v-for="skill in getSkillsBySlug('leadership')" :key="skill.id">
                                <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-space-mono mb-2 font-semibold">Quality & Collaboration</h3>
                        <ul class="flex flex-wrap justify-start gap-2" aria-label="Quality and collaboration skills">
                            <li v-for="skill in getSkillsBySlug('quality')" :key="skill.id">
                                <UBadge color="secondary" variant="soft" size="md" class="rounded-sm">{{ skill.name }}</UBadge>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-space-mono mb-2 text-lg font-semibold">Tools & Environment</h3>
                        <ul class="flex flex-wrap justify-start gap-2" aria-label="Tools and environment">
                            <li v-for="skill in getSkillsBySlug('tools')" :key="skill.id">
                                <UBadge color="secondary" variant="soft" size="md" class="rounded-lg">{{ skill.name }}</UBadge>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </SectionPanel>
    </section>
</template>
