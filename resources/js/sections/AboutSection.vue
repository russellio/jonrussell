<script setup lang="ts">
import { onMounted, ref } from 'vue';
import type { ComponentPublicInstance } from 'vue';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
const { scrollToSection } = useScrollToSection();

import { library } from '@fortawesome/fontawesome-svg-core';
import { faObjectGroup } from '@fortawesome/free-regular-svg-icons';
import { faCaretRight } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add( faCaretRight,faObjectGroup );

interface Skill {
    id: number;
    name: string;
}

interface SkillType {
    id: number;
    name: string;
    slug: string;
    skills: Skill[];
}

interface SkillsResponse {
    success: boolean;
    data: SkillType[];
}

const techStack = [
    { tech: 'Laravel', percent: '90' },
    { tech: 'PHP', percent: '95' },
    { tech: 'REST APIs', percent: '95' },
    { tech: 'MySQL / RDMS', percent: '90' },
    { tech: 'Vue', percent: '80' },
    { tech: 'React', percent: '45' },
    { tech: 'JavaScript', percent: '95' },
    { tech: 'TypeScript', percent: '80' },
    { tech: 'HTML5', percent: '95' },
    { tech: 'CSS3', percent: '95' },
    { tech: 'PEST / PHPUnit', percent: '75' },
    { tech: 'Agile', percent: '90' },
    { tech: 'OOP / MVC', percent: '95' },
];
const techStackRefs = ref<(Element | ComponentPublicInstance | null)[]>([]);

const skillTypes = ref<SkillType[]>([]);
const isLoadingSkills = ref(false);
const skillsError = ref<string | null>(null);

const getSkillsBySlug = (slug: string): Skill[] => {
    const skillType = skillTypes.value.find(st => st.slug === slug);
    return skillType?.skills || [];
};

const fetchSkills = async () => {
    isLoadingSkills.value = true;
    skillsError.value = null;

    try {
        const response = await fetch('/api/skills', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch skills');
        }

        const data: SkillsResponse = await response.json();

        if (data.success && data.data) {
            skillTypes.value = data.data;
        } else {
            throw new Error('Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching skills:', error);
        skillsError.value = error instanceof Error ? error.message : 'Failed to load skills';
    } finally {
        isLoadingSkills.value = false;
    }
};

onMounted(() => {
    fetchSkills();
});
</script>

<template>
    <section>
        <div class="mt-10 mb-16 text-center">
            <h2>About</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="description">
                <p>
                    I’m a Senior Software Engineer with more than a decade of experience designing, developing, and scaling
                    enterprise applications. I bring deep expertise in Laravel, PHP, REST APIs, JavaScript/TypeScript, and Vue.js,
                    paired with Agile leadership and a drive to keep learning new technologies.
                </p>
                <p>
                    I’ve delivered high-impact solutions across defense, finance, and manufacturing, leading cross-functional teams and
                    contributing directly to complex migrations, API integrations, workflow automation, and production debugging.
                    My background in both engineering and leadership helps me translate business goals into clear, maintainable
                    technical solutions.
                </p>
                <p>
                    I stay hands-on in every role, continuously refining my craft and evolving with modern frameworks and tools.
                    Nothing’s more rewarding than shipping a solution that streamlines a messy process, improves team efficiency,
                    and makes life easier for the people who use it.
                </p>
            </div>

            <div class="tech-stack">
                <h3>Tech Stack</h3>
                <ul class="ms-6">
                    <li v-for="(item, index) in techStack"
                        :key="item.tech"
                        :style="{ '--percent': parseInt(item.percent) / 100 }"
                        class="ms-6"
                        :ref="(el) => { techStackRefs[index] = el }"
                    >
                        <span class="fa-li text-gold"><FontAwesomeIcon :icon="faCaretRight" /></span>
                        {{ item.tech }}
                    </li>
                </ul>

                <div class="btn-wrapper">
                    <button @click="scrollToSection('projects')" class="btn-content">
                        <FontAwesomeIcon :icon="faObjectGroup" size="lg" class="me-3" />
                        View Projects
                    </button>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3>Skills & Tools</h3>

                <div v-if="isLoadingSkills" class="text-center py-8">
                    <p class="text-gray-500">Loading skills...</p>
                </div>

                <div v-else-if="skillsError" class="text-center py-8">
                    <p class="text-red-500">{{ skillsError }}</p>
                    <button
                        @click="fetchSkills"
                        class="mt-4 px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark"
                    >
                        Retry
                    </button>
                </div>

                <template v-else>
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="lg:w-3/5">
                            <h4>Software Engineering</h4>
                            <div class="skills">
                                <span
                                    v-for="skill in getSkillsBySlug('software')"
                                    :key="skill.id"
                                    class="pill"
                                >{{ skill.name }}</span>
                            </div>
                        </div>
                        <div class="lg:w-2/5">
                            <h4>Architecture & DevOps</h4>
                            <div class="skills">
                                <span
                                    v-for="skill in getSkillsBySlug('devops')"
                                    :key="skill.id"
                                    class="pill"
                                >{{ skill.name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="lg:w-1/2">
                            <h4>Quality & Collaboration</h4>
                            <div class="skills">
                                <span
                                    v-for="skill in getSkillsBySlug('quality')"
                                    :key="skill.id"
                                    class="pill"
                                >{{ skill.name }}</span>
                            </div>
                        </div>
                        <div class="lg:w-1/2">
                            <h4>Leadership & Team Building</h4>
                            <div class="skills">
                                <span
                                    v-for="skill in getSkillsBySlug('leadership')"
                                    :key="skill.id"
                                    class="pill"
                                >{{ skill.name }}</span>
                            </div>
                        </div>
                    </div>
                </template>

            </div>

            <div class="experience md:col-span-2">
                <h3 class="mb-4">Experience</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="card p-6 text-center">
                        <div class="mb-2 text-3xl font-bold text-primary">Master of Business<br> & Technology</div>
                        <img src="/images/uga-logo.png" class="mx-auto" alt="UGA Logo" />
                    </div>
                    <div class="card p-6 text-center">
                        <div class="mb-2 text-6xl font-bold text-primary">12+</div>
                        <div class="mb-2 text-2xl">Years of Professional SWE Experience</div>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="mb-2 text-6xl font-bold text-primary">2.5+</div>
                        <div class="mb-2 text-2xl">Years of Management</div>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="mb-2 text-6xl font-bold text-primary">3+</div>
                        <div class="mb-2 text-2xl">Years of Project Management</div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</template>

<style scoped>
@reference "@/css/app.css";

.description p {
    @apply md:mx-auto text-lg md:leading-relaxed text-gray-600 mb-4;
}

.skills {
    @apply mb-8 flex flex-wrap gap-2 justify-center lg:justify-start;
}

.pill {
    @apply inline-block items-center px-4 py-2 select-none;
    @apply rounded-sm font-semibold text-xs shadow-sm bg-terminal-black-100;
    @apply transition-all duration-200 ease-in-out hover:bg-terminal-black-400;
}

ul {
    @apply mt-2 ps-4 mb-10 space-y-2;
}

ul li {
    @apply relative;
}

.tech-stack ul {
    @apply mt-2 mb-6 ps-0;
}

.tech-stack ul li {
    @apply font-semibold text-terminal-black;
}

.tech-stack ul li::before {
    content: '';
    position: absolute;
    left: 140px;
    top: 50%;
    width: calc(100% - 140px);
    min-width:140px;
    height: 4px;
    background: linear-gradient(90deg, #999, #999);
    transform: translateY(-50%);
    z-index: 1;
}

.tech-stack ul li::after {
    content: '';
    position: absolute;
    left: 140px;
    top: 50%;
    width: calc((100% - 140px) * var(--percent));
    height: 4px;
    background: linear-gradient(90deg, var(--color-bright-green), var(--color-bright-green));
    transform: translateY(-50%);
    z-index: 2;
}
</style>
