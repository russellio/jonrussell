<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBitbucket, faBootstrap, faCss3, faGithub, faGitkraken, faHtml5, faLaravel, faPhp, faVuejs } from '@fortawesome/free-brands-svg-icons';
import { faHeart } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { markRaw, onMounted, reactive, shallowRef } from 'vue';
import { BraveIcon, PhpStormIcon, SentryIcon, TailwindCssIcon, ViteIcon, WarpIcon } from 'vue3-simple-icons';
library.add(faPhp, faLaravel, faGitkraken, faGithub, faBootstrap, faVuejs, faCss3, faHtml5, faBitbucket, faHeart);

const things = shallowRef([
    { title: 'Tailwind CSS', icon: 'TailwindCssIcon', type: 'si' },
    { title: 'Brave', icon: 'BraveIcon', type: 'si', color: '#FB542B' },
    { title: 'PHP', icon: 'php', type: 'fa' },
    { title: 'Laravel', icon: 'laravel', type: 'fa' },
    { title: 'Cursor', icon: 'cursor-alt.svg', type: '' },
    { title: 'Warp', icon: 'WarpIcon', type: 'si' },
    { title: 'PhpStorm', icon: 'PhpStormIcon', type: 'si' },
    { title: 'VS Code', icon: 'vscode-alt.svg', type: '' },
    { title: 'Gitkraken', icon: 'gitkraken', type: 'fa' },
    { title: 'Github', icon: 'github', type: 'fa' },
    { title: 'Bootstrap', icon: 'bootstrap', type: 'fa' },
    { title: 'Vue.js', icon: 'vuejs', type: 'fa' },
    { title: 'Sentry', icon: 'SentryIcon', type: 'si', color: '#362D59' },
    { title: 'CSS3', icon: 'css3', type: 'fa' },
    { title: 'HTML5', icon: 'html5', type: 'fa' },
    { title: 'Bitbucket', icon: 'bitbucket', type: 'fa' },
    { title: 'Vite', icon: 'ViteIcon', type: 'si' },
]);

const iconRegistry: Record<string, any> = {
    TailwindCssIcon: markRaw(TailwindCssIcon),
    WarpIcon: markRaw(WarpIcon),
    PhpStormIcon: markRaw(PhpStormIcon),
    ViteIcon: markRaw(ViteIcon),
    BraveIcon: markRaw(BraveIcon),
    SentryIcon: markRaw(SentryIcon),
};

const simpleIconMap = reactive<Record<string, any>>({});

const hoveredIndex = shallowRef<string | null>(null);

const setHovered = (index: string | null) => {
    hoveredIndex.value = index;
};

onMounted(() => {
    things.value.forEach((thing) => {
        if (thing.type === 'si' && thing.icon && iconRegistry[thing.icon]) {
            simpleIconMap[thing.icon] = iconRegistry[thing.icon];
        } else if (thing.type === 'si' && thing.icon && !iconRegistry[thing.icon]) {
            console.warn(`Add icon "${thing.icon}" to the vue3-simple-icons imports and iconRegistry.`);
        }
    });
});
</script>

<template>
    <div class="things">
        <div class="w-full text-center font-sixtyfour text-2xl text-white uppercase opacity-30">
            Things I <FontAwesomeIcon :icon="['fas', 'heart']" />
        </div>
        <div class="animate-infinite-scroll flex w-max flex-nowrap items-center justify-center md:justify-start">
            <ul
                v-for="index in 2"
                :key="index"
                class="m-0 flex shrink-0 flex-nowrap items-center justify-center p-0 md:justify-start [&_img]:max-w-none [&_li]:mx-8"
            >
                <li
                    v-for="(thing, idx) in things"
                    :key="`${index}-${idx}`"
                    class="icon-item flex flex-col items-center whitespace-nowrap text-white opacity-40"
                    @mouseenter="setHovered(`${index}-${idx}`)"
                    @mouseleave="setHovered(null)"
                >
                    <template v-if="thing.icon">
                        <img
                            v-if="!thing.type"
                            :src="`/images/icons/${thing.icon}`"
                            class="h-12 w-12 fill-white align-top"
                            style="filter: grayscale(100%)"
                            :alt="thing.title"
                        />
                        <FontAwesomeIcon v-else-if="thing.type === 'fa'" :icon="['fab', thing.icon]" class="text-5xl" />
                        <component v-else-if="simpleIconMap[thing.icon]" :is="simpleIconMap[thing.icon]" class="h-12 w-12 fill-white" />
                        <span v-else>{{ thing.title }}</span>
                    </template>
                    <transition name="fade">
                        <div v-show="hoveredIndex === `${index}-${idx}`" class="icon-title">
                            {{ thing.title }}
                        </div>
                    </transition>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.things {
    @apply mask-[linear-gradient(to_right,transparent_0,black_128px,black_calc(100%-200px),transparent_100%)];
    width: 100%;
    overflow-x: hidden;
}

.icon-title {
    @apply rounded text-xs;
    color: #cccccc;
}

.icon-item {
    margin-top: 1rem;
    height: 4rem;
    width: 4rem;
    transition: opacity 0.2s ease;
}

.icon-item:hover {
    opacity: 1;
}

.fade-enter-active,
.fade-leave-active {
    transition:
        opacity 0.3s ease,
        transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}
</style>
