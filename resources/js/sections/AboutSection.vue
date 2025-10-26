<script setup lang="ts">
const stats = [
    { value: '5+', label: 'Years Experience' },
    { value: '50+', label: 'Projects Completed' },
    { value: '100%', label: 'Client Satisfaction' },
    { value: '24/7', label: 'Support Available' }
];
</script>

<template>
    <section>
        <!-- Page Header -->
        <div class="mt-10 mb-16 text-center">
            <h2>About</h2>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="description">
                <p>
                    Currently a Senior Software Engineer with expertise in Laravel, PHP, and full-stack development.
                    I specialize in building high-performance APIs, database optimization, and creating seamless
                    integrations that bridge the gap between frontend and backend systems.
                </p>
                <p>
                    Currently a Senior Software Engineer with expertise in Laravel, PHP, and full-stack development.
                    I specialize in building high-performance APIs, database optimization, and creating seamless
                    integrations.
                </p>
                <div class="btn-wrapper">
                    <button @click="() => scrollToSection('projects')" class="content-btn">Projects</button>
                </div>
            </div>

            <div class="tech-stack">
                <h3>Tech Stack</h3>
                <ul>
                    <li style="--percent: 85%">&middot; Laravel</li>
                    <li style="--percent: 90%">&middot; PHP</li>
                    <li style="--percent: 85%">&middot; MySQL / RDMS</li>
                    <li style="--percent: 75%">&middot; Vue</li>
                    <li style="--percent: 40%">&middot; React</li>
                    <li style="--percent: 95%">&middot; JavaScript</li>
                    <li style="--percent: 80%">&middot; TypeScript</li>
                    <li style="--percent: 95%">&middot; HTML5</li>
                    <li style="--percent: 95%">&middot; CSS3</li>
                    <li style="--percent: 75%">&middot; PEST / PHPUnit</li>
                    <li style="--percent: 90%">&middot; Agile</li>
                </ul>
            </div>

            <div class="toolkit col-span-2">
                <h3>Toolkit</h3>
                <div class="skills">
                    <span class="pill">JavaScript</span><span class="pill">Node.js</span
                ><span class="pill">TypeScript</span><span class="pill">CSS</span><span class="pill">SASS</span
                ><span class="pill">SQL</span><span class="pill">Vite</span><span class="pill">webpack</span
                ><span class="pill">Astro</span><span class="pill">Jest</span><span class="pill">Cypress</span
                ><span class="pill">CLI</span><span class="pill">Git</span><span class="pill">SEO</span
                ><span class="pill">a11y</span><span class="pill">Vercel</span>
                </div>
            </div>

        </div>

        <!-- Experience Section -->
        <h3>Experience</h3>
        <p class="text-center">
            It's not just about writing code. It's about building solutions that scale and deliver real business value.
        </p>

        <div class="flex flex-col md:flex-row">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="(stat, index) in stats" :key="index" class="card p-6 text-center">
                    <div class="text-primary-600 mb-2 text-3xl font-bold">{{ stat.value }}</div>
                    <div class="text-gray-600">{{ stat.label }}</div>
                </div>
            </div>
        </div>

    </section>
</template>


<style scoped>
@reference "@/css/app.css";

.description {
    @apply mb-16;
}

.description p {
    @apply mx-auto max-w-4xl text-lg leading-relaxed text-gray-600 mb-4;
}

.skills {
    @apply mb-8 flex flex-wrap justify-center gap-2;
}

.pill {
    @apply inline-flex w-max items-center border-1 border-terminal-black-300 select-none;
    @apply rounded-full p-0.5 text-xs shadow-sm;
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
    @apply text-sm font-semibold text-terminal-black;
}

.tech-stack ul li::before {
    content: '';
    position: absolute;
    left: 130px;
    top: 50%;
    width: calc(100% - 120px);
    height: 4px;
    background: linear-gradient(90deg, #999, #999);
    transform: translateY(-50%);
    z-index: 1;
}

.tech-stack ul li::after {
    content: '';
    position: absolute;
    left: 130px;
    top: 50%;
    width: calc(var(--percent) - 100px);
    height: 4px;
    background: linear-gradient(90deg, var(--color-bright-green), var(--color-bright-green));
    transform: translateY(-50%);
    z-index: 2;
}

/*.card {
    @apply bg-white rounded-lg shadow-md border border-gray-200;
}*/
</style>
