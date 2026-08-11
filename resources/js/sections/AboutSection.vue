<script setup lang="ts">
import SectionHeading from '@/js/components/SectionHeading.vue';

const highlights = [
    { value: '13+ yrs', label: 'Software engineering' },
    { value: '3+ yrs', label: 'Project Management' },
    { value: '2.5+ yrs', label: 'Management' },
    { value: 'Master of Internet Techology', label: 'University of Georgia' },
];
</script>

<template>
    <section id="about" class="mb-16 flex scroll-mt-16 flex-col text-base md:mb-24 lg:mt-0 lg:mb-20 lg:scroll-mt-20 lg:pt-0" aria-label="About me">
        <SectionHeading title="About Me" />
        <div class="gap-6 rounded-br-2xl rounded-bl-2xl border-b border-brand-red bg-black/30 px-6 pt-3 pb-10 text-sm">
            <p class="my-4 text-base">
                <span class="me-4 text-3xl font-bold text-white">Hello!</span>
                I'm Jon, a full stack engineer with 13+ years of experience designing, developing, and scaling enterprise applications. My primary
                stack includes
                <span class="text-highlight">Laravel</span>, <span class="text-highlight">PHP</span>, <span class="text-highlight">MySQL</span>,
                <span class="text-highlight">REST APIs</span>, <span class="text-highlight">JavaScript</span>,
                <span class="text-highlight">TypeScript</span>
                and <span class="text-highlight">Vue.js</span>, backed by Agile leadership and a steady drive to learn and adapt to new platforms and
                technologies.
            </p>

            <p class="my-4 text-base">
                I enjoy working on meaningful problems with a clear business impact. I particularly enjoy building internal tools, automating tedious
                workflows, and improving developer experience. I do my best work when I'm given a problem, the context I need, and the space to figure
                out the best solution.
            </p>

            <div class="my-4 border-s border-slate-400 ps-2">
                <p class="my-4 ms-4">
                    <span class="text-inline-heading">experience:</span> My career has spanned defense, finance, and manufacturing, where I've built
                    high-impact solutions and led cross-functional teams through complex technical challenges.
                </p>

                <p class="my-4 ms-4">
                    <span class="text-inline-heading">work ethic:</span> From complex migrations and API integrations to workflow automation and
                    production debugging, I take ownership from start to finish. I value accountability, transparency, clear communication, and mutual
                    respect.
                </p>

                <p class="my-4 ms-4">
                    <span class="text-inline-heading">tinkering:</span> I love the problem-solving side of engineering. There's something satisfying
                    about taking something messy, manual, or frustrating and turning it into something that just works. In my spare time, I build
                    projects around my homelab, DigitalOcean droplets, AWS EC2, and locally running LLMs.
                </p>
            </div>

            <p class="my-4 text-base">
                I've always preferred staying hands-on, exploring new frameworks and technologies, and seeing what’s coming next. To me, the most
                satisfying part of engineering is delivering solutions that simplify processes, improve efficiency, and make life easier for the
                people who depend on them, while building something I can genuinely be proud of.
            </p>

            <ul class="flex flex-wrap justify-center gap-x-3 gap-y-2 pt-2" aria-label="Highlights">
                <li v-for="highlight in highlights" :key="highlight.label" class="text-sm">
                    <span class="text-base font-semibold text-primary">
                        {{ highlight.value }}
                    </span>
                    <span class="text-sm text-slate-100"> · {{ highlight.label }}</span>
                </li>
            </ul>
        </div>
    </section>
</template>
