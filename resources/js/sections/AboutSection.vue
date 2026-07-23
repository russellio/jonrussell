<script setup lang="ts">
const highlights = [
    { value: 'M.B.T.', label: 'University of Georgia' },
    { value: '13+ yrs', label: 'software engineering' },
    { value: '2.5+ yrs', label: 'management' },
    { value: '3+ yrs', label: 'project management' },
];
</script>

<template>
    <section id="about" class="mb-16 scroll-mt-16 md:mb-24 lg:mb-36 lg:scroll-mt-24" aria-label="About me">
        <div
            class="sticky top-0 z-20 -mx-6 mb-4 w-screen bg-slate-900/75 px-6 py-5 backdrop-blur md:-mx-12 md:px-12 lg:sr-only lg:relative lg:top-auto lg:mx-auto lg:w-full lg:px-0 lg:py-0 lg:opacity-0"
        >
            <h2 class="text-sm font-bold tracking-widest text-slate-200 uppercase lg:sr-only">About Me</h2>
        </div>

        <div class="space-y-4">
            <p>
                As a Senior Software Engineer with over a decade of experience, I specialize in designing, developing, and scaling enterprise
                applications. My work centers on <span class="font-medium text-slate-200">Laravel</span>, PHP, REST APIs, JavaScript/TypeScript, and
                <span class="font-medium text-slate-200">Vue.js</span>, backed by Agile leadership and a steady drive to learn and adapt to new
                technologies.
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

            <ul class="flex flex-wrap gap-x-6 gap-y-2 pt-2" aria-label="Highlights">
                <li v-for="highlight in highlights" :key="highlight.label" class="text-sm">
                    <span class="font-semibold text-teal-300">{{ highlight.value }}</span>
                    <span class="text-slate-500"> · {{ highlight.label }}</span>
                </li>
            </ul>
        </div>
    </section>
</template>
