<script setup lang="ts">
import { useApiFetch } from '@/js/composables/useApi';
import { useScrollToSection } from '@/js/composables/useScrollToSection';
import { ComponentPublicInstance, onMounted, ref } from 'vue';
const { scrollToSection } = useScrollToSection();

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCss3, faHtml5, faJs, faLaravel, faPhp, faReact, faVuejs } from '@fortawesome/free-brands-svg-icons';
import { faObjectGroup } from '@fortawesome/free-regular-svg-icons';
import { faCode, faDatabase, faProjectDiagram, faSitemap, faVial } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { MySqlIcon, PythonIcon, ReactIcon, TypeScriptIcon } from 'vue3-simple-icons';

const simpleIcons = [{ component: TypeScriptIcon }, { component: ReactIcon }, { component: MySqlIcon }, { component: PythonIcon }];

const faIcons = [
    { group: 'far', name: faObjectGroup },
    { group: 'fas', name: faDatabase },
    { group: 'fas', name: faCode },
    { group: 'fas', name: faVial },
    { group: 'fas', name: faProjectDiagram },
    { group: 'fas', name: faSitemap },
    { group: 'fab', name: faLaravel },
    { group: 'fab', name: faPhp },
    { group: 'fab', name: faVuejs },
    { group: 'fab', name: faReact },
    { group: 'fab', name: faJs },
    { group: 'fab', name: faHtml5 },
    { group: 'fab', name: faCss3 },
];

faIcons.forEach((icon) => {
    library.add(icon.name);
});

const reverseKebabCase = (kebabCaseString: string): string => kebabCaseString.split('-').reverse().join('-');
const getFaIcon = (iconName: string): [string, string] => {
    const icon = faIcons.find((icon) => reverseKebabCase(icon.name.iconName) === iconName);
    return icon ? [icon.group, iconName] : ['', ''];
};

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

interface TechStackItem {
    tech: string;
    percent: string;
    iconType: string;
    iconName: string;
    active?: boolean;
}

const techStackRefs = ref<(Element | ComponentPublicInstance | null)[]>([]);
const isLoadingTechStack = ref(false);
const techStackError = ref<string | null>(null);

// Use API composable for tech stack
const {
    data: techStack,
    loading: isLoadingTechStack,
    error: techStackError,
    execute: fetchTechStack,
} = useApiFetch<TechStackItem[]>('/api/tech-stack', {
    errorMessage: 'Failed to load tech stack',
    showSuccessToast: false, // No success toast for initial page load
    showErrorToast: true,
});

// Use API composable for skills
const {
    data: skillTypes,
    loading: isLoadingSkills,
    error: skillsError,
    execute: fetchSkills,
} = useApiFetch<SkillType[]>('/api/skills', {
    errorMessage: 'Failed to load skills',
    showSuccessToast: false, // No success toast for initial page load
    showErrorToast: true,
});

const getSkillsBySlug = (slug: string): Skill[] => {
    if (!skillTypes.value) return [];
    const skillType = skillTypes.value.find((st) => st.slug === slug);
    return skillType?.skills || [];
};

const getSimpleIcon = (iconName: string) => {
    return simpleIcons.find((icon) => icon.component.__name === iconName)?.component || '';
};

const fetchTechStack = async () => {
    isLoadingTechStack.value = true;
    techStackError.value = null;

    try {
        const response = await fetch('/api/tech-stack', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch tech stack');
        }

        const data: TechStackResponse = await response.json();

        if (data.success && data.data) {
            techStack.value = data.data;
        } else {
            throw new Error('Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching tech stack:', error);
        techStackError.value = error instanceof Error ? error.message : 'Failed to load tech stack';
        // Fallback to empty array on error
        techStack.value = [];
    } finally {
        isLoadingTechStack.value = false;
    }
};

onMounted(() => {
    fetchSkills();
    fetchTechStack();
});
</script>

<template>
    <section class="md:pb-10!">
        <div class="mt-10 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="description">
                <h2 class="md:mb-6! md:text-start! md:text-4xl!">About Me</h2>
                <p>
                    As a Senior Software Engineer with over a decade of experience, I specialize in designing, developing, and scaling enterprise
                    applications. My work centers on Laravel, PHP, REST APIs, JavaScript/TypeScript, and Vue.js, backed by Agile leadership and a
                    steady drive to learn and adapt to new technologies.
                </p>

                <p>
                    Over the years, my projects have spanned defense, finance, and manufacturing, where I've led cross-functional teams and built
                    high-impact solutions. From complex migrations and API integrations to workflow automation and production debugging, I focus on
                    translating business goals into clear, maintainable technical outcomes that last.
                </p>

                <p>
                    Staying hands-on keeps me grounded. I'm always refining my craft, exploring modern frameworks, and finding better ways to solve
                    problems. The best part of the job is delivering solutions that simplify processes, boost efficiency, and make life easier for the
                    people who depend on them.
                </p>
            </div>

            <div class="tech-stack content-top pt-0 xl:content-end">
                <h3>Tech Stack</h3>
                <div v-if="isLoadingTechStack" class="py-4 text-center">
                    <p class="text-gray-500">Loading tech stack...</p>
                </div>
                <div v-else-if="techStackError" class="py-4 text-center">
                    <p class="text-red-500">{{ techStackError }}</p>
                    <button @click="fetchTechStack" class="hover:bg-primary-dark mt-2 rounded bg-primary px-4 py-2 text-white">Retry</button>
                </div>
                <ul v-else-if="techStack" class="ms-6">
                    <li
                        v-for="(item, index) in techStack"
                        :key="item.tech"
                        :style="{ '--percent': parseInt(item.percent) / 100 }"
                        class="ms-6"
                        :class="{ active: item.active }"
                        :ref="
                            (el) => {
                                techStackRefs[index] = el;
                            }
                        "
                    >
                        <span v-if="item.iconType === 'fa' && item.iconName" class="fa-li">
                            <FontAwesomeIcon :icon="getFaIcon(item.iconName)" />
                        </span>
                        <component
                            v-else-if="item.iconType === 'si' && getSimpleIcon(item.iconName)"
                            :is="getSimpleIcon(item.iconName)"
                            class="-ms-6 inline-block h-5 w-5 fill-current"
                        />
                        {{ item.tech }}
                        <div v-if="item.active" class="position-absolute mt-[-4px] mb-[-4px] text-end text-xs text-gray-500">(current focus)</div>
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

                <div v-if="isLoadingSkills" class="py-8 text-center">
                    <p class="text-gray-500">Loading skills...</p>
                </div>

                <div v-else-if="skillsError" class="py-8 text-center">
                    <p class="text-red-500">{{ skillsError }}</p>
                    <button @click="fetchSkills" class="hover:bg-primary-dark mt-4 rounded bg-primary px-4 py-2 text-white">Retry</button>
                </div>

                <template v-else>
                    <div class="flex flex-col">
                        <h4>Software Engineering</h4>
                        <div class="skills justify-center">
                            <span v-for="skill in getSkillsBySlug('software')" :key="skill.id" class="pill">{{ skill.name }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-6 md:flex-row">
                        <div>
                            <h4 class="text-center! lg:text-start!">Architecture & DevOps</h4>
                            <div class="skills justify-center lg:justify-start">
                                <span v-for="skill in getSkillsBySlug('devops')" :key="skill.id" class="pill">{{ skill.name }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-center!">Quality & Collaboration</h4>
                            <div class="skills justify-center">
                                <span v-for="skill in getSkillsBySlug('quality')" :key="skill.id" class="pill">{{ skill.name }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-center! lg:text-end!">Leadership & Team Building</h4>
                            <div class="skills justify-center lg:justify-end">
                                <span v-for="skill in getSkillsBySlug('leadership')" :key="skill.id" class="pill">{{ skill.name }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="experience md:col-span-2">
                <h3 class="mb-4">Experience</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="card p-4 text-center">
                        <div class="mb-2 text-2xl font-bold text-primary">
                            Master of Business<br />
                            & Technology
                        </div>
                        <img src="/images/uga-logo.png" class="mx-auto" alt="UGA Logo" />
                    </div>

                    <div class="card flex flex-col items-center justify-center gap-2 p-4 sm:flex-row lg:flex-row">
                        <div class="text-end text-4xl font-bold text-primary">10+</div>
                        <div class="text-lg">Years of Professional SWE Experience</div>
                    </div>

                    <div class="card flex flex-col items-center justify-center gap-2 p-4 sm:flex-row lg:flex-row">
                        <div class="text-end text-4xl font-bold text-primary lg:text-2xl xl:text-4xl">2.5+</div>
                        <div class="text-xl">Years of Management</div>
                    </div>

                    <div class="card flex flex-col items-center justify-center gap-2 p-4 sm:flex-row lg:flex-row">
                        <div class="text-end text-4xl font-bold text-primary">3+</div>
                        <div class="text-xl">Years of Project Management</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@reference "@/css/app.css";

.description p {
    @apply mb-4 text-lg text-gray-600 md:mx-auto md:leading-relaxed;
}

.skills {
    @apply mb-8 flex flex-wrap gap-2;
}

.pill {
    @apply inline-block items-center px-4 py-1 select-none;
    @apply rounded-md border border-terminal-black/60 bg-white/50 font-semibold shadow-md;
}

ul {
    @apply mt-2 mb-10 space-y-2 ps-4;
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
    min-width: 140px;
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

.tech-stack ul li.active::after {
    background: linear-gradient(to right, white, var(--color-bright-green));
    background-size: 200% 100%;
    animation-delay: 1s;
    animation: gradientAnimation 2s linear infinite reverse;
}

@keyframes gradientAnimation {
    0% {
        background-position: 0;
    }

    100% {
        background-position: 200% 0;
    }
}
</style>
