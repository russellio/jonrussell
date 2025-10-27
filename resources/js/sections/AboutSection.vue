<script setup lang="ts">
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
                    <li style="--percent: 90%">&middot; Laravel</li>
                    <li style="--percent: 95%">&middot; PHP</li>
                    <li style="--percent: 95%">&middot; REST APIs</li>
                    <li style="--percent: 90%">&middot; MySQL / RDMS</li>
                    <li style="--percent: 80%">&middot; Vue</li>
                    <li style="--percent: 40%">&middot; React</li>
                    <li style="--percent: 95%">&middot; JavaScript</li>
                    <li style="--percent: 80%">&middot; TypeScript</li>
                    <li style="--percent: 95%">&middot; HTML5</li>
                    <li style="--percent: 95%">&middot; CSS3</li>
                    <li style="--percent: 75%">&middot; PEST / PHPUnit</li>
                    <li style="--percent: 90%">&middot; Agile</li>
                    <li style="--percent: 95%">&middot; OOP / MVC</li>
                </ul>
            </div>

            <div class="toolkit col-span-2">
                <h3>Skills & Tools</h3>

                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="w-2/3">
                        <h4>Software Engineering</h4>
                        <div class="skills">
                            <span class="pill">Node.js</span>
                            <span class="pill">SQL</span>
                            <span class="pill">SCSS</span>
                            <span class="pill">Bootstrap</span>
                            <span class="pill">jQuery</span>
                            <span class="pill">JSON</span>
                            <span class="pill">XML</span>
                            <span class="pill">Vuex</span>
                            <span class="pill">Pinia</span>
                            <span class="pill">SaaS</span>
                            <span class="pill">SDLC</span>
                            <span class="pill">OOP</span>
                            <span class="pill">MVC</span>
                            <span class="pill">Jira</span>
                            <span class="pill">webpack</span>
                            <span class="pill">Vite</span>
                            <span class="pill">Git</span>
                            <span class="pill">SEO</span>
                            <span class="pill">a11y</span>
                        </div>
                    </div>
                    <div class="w-1/3">
                        <h4>Architecture & DevOps</h4>
                        <div class="skills">
                            <span class="pill">AWS</span>
                            <span class="pill">Docker</span>
                            <span class="pill">CI/CD automation</span>
                            <span class="pill">Azure Pipelines</span>
                            <span class="pill">Jenkins</span>
                            <span class="pill">Github</span>
                            <span class="pill">Bitbucket</span>
                            <span class="pill">Linux</span>
                        </div>
                    </div>
                    </div>
                <div class="flex flex-col sm:flex-row">
                    <div class="w-1/2">
                        <h4>Quality & Collaboration</h4>
                        <div class="skills">
                            <span class="pill">Unit testing</span>
                            <span class="pill">PEST</span>
                            <span class="pill">PHPUnit</span>
                            <span class="pill">Code reviews</span>
                            <span class="pill">Pair programming</span>
                            <span class="pill">API optimization</span>
                            <span class="pill">Plays well with others</span>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <h4>Leadership & Team Building</h4>
                        <div class="skills">
                            <span class="pill">Team mentoring</span>
                            <span class="pill">Innovative process improvement</span>
                            <span class="pill">Experienced Manager, Project Manager</span>
                            <span class="pill">ScrumMaster Certification (CSM)</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <h3 class="mb-3">Experience</h3>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
            <div class="card p-6 text-center">
                <div class="mb-2 text-3xl font-bold text-primary">Master of Business<br /> & Technology</div>
                <img src="/images/uga-logo.png" class="max-w-3/4 mx-auto" alt="UGA Logo" />
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
    @apply mb-8 flex flex-wrap gap-2;
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
</style>
