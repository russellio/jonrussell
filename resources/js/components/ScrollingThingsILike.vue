<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
library.add(fab);

const things = [
    { title: 'PHP', icon: 'php', type: 'fa' },
    { title: 'Laravel', icon: 'laravel', type: 'fa' },
    { title: 'Cursor', icon: 'CursorA', type: 'si' },
    { title: 'Warp', icon: ''},
    { title: 'PHPStorm', icon: '' },
    { title: 'VSCode', icon: '' },
    { title: 'Gitkraken', icon: 'gitkraken' },
    { title: 'Github', icon: 'github' },
    { title: 'Bootstrap', icon: 'bootstrap' },
    { title: 'Vue.js', icon: 'vuejs' },
    { title: 'CSS3', icon: 'css3' },
    { title: 'HTML5', icon: 'html5' },
    { title: 'Bitbucket', icon: 'bitbucket' },
    { title: 'Tailwind CSS', icon: '' },
    { title: 'Vite', icon: '' },
];

const getIcon = (icon) => {
    let thing = things.find(thing => icon === thing.icon);
    if (!thing) return icon;

    let output;
    switch (thing.type) {
        case 'fa':
            output = `<FontAwesomeIcon :icon="['fab', ${thing.icon}]" class="fa-2xl" />`;
            break;
        case 'si':
            output = `< />`;
            break;
        default:
            output = ``
    }
    return output;
}
</script>

<template>
    <div class="w-full overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-200px),transparent_100%)]">
        <div class="flex items-center justify-center md:justify-start flex-nowrap animate-infinite-scroll">
            <ul v-for="index in 2" :key="index" class="flex items-center justify-center md:justify-start flex-nowrap [&_li]:mx-8 [&_img]:max-w-none">
                <li v-for="(thing, idx) in things" :key="`${index}-${idx}`" class="thing whitespace-nowrap text-white">
                    <FontAwesomeIcon v-if="thing.icon" :icon="['fab', thing.icon]" :alt="thing.title" class="fa-2xl" />
                    <div v-if="!thing.icon && thing.title" v-html="thing.title" />
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.thing {
    color: #ffffff;
}

</style>
