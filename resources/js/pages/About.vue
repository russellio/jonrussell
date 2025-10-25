<script setup lang="ts">
import App from '../App.vue';
import AboutSection from '../sections/AboutSection.vue'

// const approaches = [
//     {
//         title: 'API Development',
//         description: 'Designing and implementing RESTful APIs with proper authentication, rate limiting, and comprehensive documentation.',
//     },
//     {
//         title: 'Database Optimization',
//         description: 'Optimizing database performance through proper indexing, query optimization, and efficient data structures.',
//     },
//     {
//         title: 'System Architecture',
//         description: 'Building scalable backend architectures that can handle growth and maintain performance under load.',
//     },
//     {
//         title: 'Code Quality',
//         description: 'Writing clean, maintainable code with comprehensive testing and following best practices and design patterns.',
//     },
// ];

</script>

<template>
    <App>
        <AboutSection />

<!--        <section>-->
<!--            &lt;!&ndash; Page Header &ndash;&gt;-->
<!--            <div class="mt-10 mb-16 text-center">-->
<!--                <h2>About</h2>-->
<!--&lt;!&ndash;                <p class="text-2xl text-terminal-black-500">A collection of projects I've worked on.</p>&ndash;&gt;-->
<!--            </div>-->

<!--            <div class="description">-->
<!--                <p>-->
<!--                    Currently a Senior Software Engineer with expertise in Laravel, PHP, and full-stack development.-->
<!--                    I specialize in building high-performance APIs, database optimization, and creating seamless-->
<!--                    integrations that bridge the gap between frontend and backend systems.-->
<!--                </p>-->
<!--                <p>-->
<!--                    Currently a Senior Software Engineer with expertise in Laravel, PHP, and full-stack development.-->
<!--                    I specialize in building high-performance APIs, database optimization, and creating seamless-->
<!--                    integrations.-->
<!--                </p>-->
<!--                <button>Projects</button>-->
<!--            </div>-->

<!--            <div class="skills">-->
<!--                <div class="title2">My toolkit</div>-->
<!--                <div class="skills-list">-->
<!--                    <span class="skill-block">JavaScript</span><span class="skill-block">Node.js</span-->
<!--                    ><span class="skill-block">TypeScript</span><span class="skill-block">CSS</span><span class="skill-block">SASS</span-->
<!--                    ><span class="skill-block">SQL</span><span class="skill-block">Vite</span><span class="skill-block">webpack</span-->
<!--                    ><span class="skill-block">Astro</span><span class="skill-block">Jest</span><span class="skill-block">Cypress</span-->
<!--                    ><span class="skill-block">CLI</span><span class="skill-block">Git</span><span class="skill-block">SEO</span-->
<!--                    ><span class="skill-block">a11y</span><span class="skill-block">Vercel</span>-->
<!--                </div>-->
<!--                <button>Resume</button>-->
<!--            </div>-->

<!--            <ul>-->
<!--                <li style="&#45;&#45;percent: 97.5%">JavaScript</li>-->
<!--                <li style="&#45;&#45;percent: 97.5%">React</li>-->
<!--                <li style="&#45;&#45;percent: 97.5%">CSS</li>-->
<!--                <li style="&#45;&#45;percent: 90%">Angular</li>-->
<!--                <li style="&#45;&#45;percent: 90%">TypeScript</li>-->
<!--                <li style="&#45;&#45;percent: 90%">Automated Testing</li>-->
<!--                <li style="&#45;&#45;percent: 75%">Node</li>-->
<!--                <li style="&#45;&#45;percent: 60%">Prompt Engineering</li>-->
<!--                <li style="&#45;&#45;percent: 25%">DevOps</li>-->
<!--                <li style="&#45;&#45;percent: 20%">Design</li>-->
<!--            </ul>-->

<!--            <div class="mb-16 text-center">-->
<!--                <p class="mx-auto max-w-4xl text-lg leading-relaxed text-gray-600">-->
<!--                    Currently a Senior Software Engineer with expertise in Laravel, PHP, and full-stack development. I specialize in building-->
<!--                    high-performance APIs, database optimization, and creating seamless integrations that bridge the gap between frontend and-->
<!--                    backend systems.-->
<!--                </p>-->
<!--            </div>-->

<!--            &lt;!&ndash; Approach Section &ndash;&gt;-->
<!--            <div class="mb-16">-->
<!--                <h2 class="mb-8 text-center text-3xl font-bold text-gray-900">My Approach</h2>-->
<!--                <p class="mx-auto mb-12 max-w-3xl text-center text-lg text-gray-600">-->
<!--                    My comprehensive development approach that builds reliable and maintainable backend systems.-->
<!--                </p>-->

<!--                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">-->
<!--                    <div v-for="(approach, index) in approaches" :key="index" class="card p-6">-->
<!--                        <div class="mb-4 flex items-center">-->
<!--                            <div class="bg-primary-600 mr-4 flex h-10 w-10 items-center justify-center rounded-full text-lg font-bold text-white">-->
<!--                                {{ index + 1 }}-->
<!--                            </div>-->
<!--                            <h3 class="text-xl font-bold text-gray-900">{{ approach.title }}</h3>-->
<!--                        </div>-->
<!--                        <p class="leading-relaxed text-gray-600">{{ approach.description }}</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            &lt;!&ndash; Experience Section &ndash;&gt;-->
<!--            <div>-->
<!--                <h2 class="mb-8 text-center text-3xl font-bold text-gray-900">Experience</h2>-->
<!--                <p class="mx-auto mb-12 max-w-3xl text-center text-lg text-gray-600">-->
<!--                    It's not just about writing code. It's about building solutions that scale and deliver real business value.-->
<!--                </p>-->

<!--                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">-->
<!--                    <div v-for="(stat, index) in stats" :key="index" class="card p-6 text-center">-->
<!--                        <div class="text-primary-600 mb-2 text-3xl font-bold">{{ stat.value }}</div>-->
<!--                        <div class="text-gray-600">{{ stat.label }}</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--        </section>-->

    </App>
</template>
