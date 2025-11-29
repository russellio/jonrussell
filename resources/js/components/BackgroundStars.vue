<script setup lang="ts">
import { onMounted, ref } from 'vue';

// Define emits for parent communication
const emit = defineEmits<{
    'background-ready': [];
}>();

const starsContainer = ref<HTMLElement>();
const starsCrossContainer = ref<HTMLElement>();
const starsCrossAuxContainer = ref<HTMLElement>();

// Color palette for night sky
const nightSky = ['#280F36', '#632B6C', '#BE6590', '#FFC1A0', '#FE9C7F'];

function getRandomInt(min: number, max: number): number {
    return Math.floor(Math.random() * (max - min)) + min;
}

function createStarElement(starClass: string, top: number, left: number, duration: number, color?: string, shadow?: string): HTMLElement {
    const star = document.createElement('div');
    star.className = `star ${starClass}`;

    let style = `top:${top}vh;left:${left}vw;animation-duration:${duration}s;`;

    if (color) {
        style += `background-color:${color};`;
    }

    if (shadow) {
        style += `box-shadow:0px 0 6px 1px ${shadow};`;
    }

    star.style.cssText = style;
    return star;
}

function createBlurElement(top: number, left: number, color: string): HTMLElement {
    const blur = document.createElement('div');
    blur.className = 'blur';
    blur.style.cssText = `top:${top}%;left:${left}%;background-color:${color}`;
    return blur;
}

function createStarWithPercentage(starClass: string, top: number, left: number, duration: number, color: string, shadow: string): HTMLElement {
    const star = document.createElement('div');
    star.className = `star ${starClass}`;
    star.style.cssText = `top:${top}%;left:${left}%;animation-duration:${duration}s;background-color:${color};box-shadow:0px 0 6px 1px ${shadow};`;
    return star;
}

function createStar2WithPercentage(top: number, left: number, duration: number, color: string, shadow: string): HTMLElement {
    const star = document.createElement('div');
    star.className = 'star star-2';
    star.style.cssText = `top:${top}%;left:${left}%;animation-duration:${duration}s;background-color:${color};box-shadow:0px 0 10px 1px ${shadow};opacity:0.7`;
    return star;
}

function generateStars() {
    if (!starsContainer.value || !starsCrossContainer.value || !starsCrossAuxContainer.value) {
        return;
    }

    const starsFragment = document.createDocumentFragment();
    const crossFragment = document.createDocumentFragment();
    const auxFragment = document.createDocumentFragment();

    for (let i = 0; i < 250; i++) {
        starsFragment.appendChild(createStarElement('star-0', getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(1, 2)));
        starsFragment.appendChild(createStarElement('star-1 blink', getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(2, 5)));
        starsFragment.appendChild(createStarElement('star-2 blink', getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(1, 4)));
        starsFragment.appendChild(createStarElement('star-3 blink', getRandomInt(0, 70), getRandomInt(0, 100), getRandomInt(5, 7)));
    }

    for (let i = 0; i < 150; i++) {
        starsFragment.appendChild(createStarElement('star-4 blink', getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(5, 7)));

        const color = nightSky[Math.ceil(getRandomInt(0, nightSky.length - 1))];
        crossFragment.appendChild(createBlurElement(getRandomInt(0, 100), getRandomInt(0, 100), color));
        crossFragment.appendChild(
            createStarWithPercentage('star-1 blink', getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(6, 12), color, color),
        );
    }

    for (let i = 0; i < 50; i++) {
        if (i % 2 === 0) {
            const color = nightSky[Math.ceil(getRandomInt(0, nightSky.length - 1))];
            starsFragment.appendChild(createStarElement('star-5', getRandomInt(0, 50), getRandomInt(0, 100), getRandomInt(5, 7), color));
        }

        const color = nightSky[Math.ceil(getRandomInt(0, nightSky.length - 1))];
        auxFragment.appendChild(createBlurElement(getRandomInt(0, 100), getRandomInt(0, 100), color));
        auxFragment.appendChild(createStar2WithPercentage(getRandomInt(0, 100), getRandomInt(0, 100), getRandomInt(4, 10), color, color));
    }

    starsContainer.value.appendChild(starsFragment);
    starsCrossContainer.value.appendChild(crossFragment);
    starsCrossAuxContainer.value.appendChild(auxFragment);
}

onMounted(() => {
    requestAnimationFrame(() => {
        generateStars();
        emit('background-ready');
    });
});
</script>

<template>
    <div class="sky">
        <div class="mountains">
            <div class="land-1"></div>
            <div class="land-2"></div>
            <div class="land-3"></div>
        </div>
        <div class="sky-base"></div>
        <div class="stars" ref="starsContainer"></div>
        <div class="stars-cross" ref="starsCrossContainer"></div>
        <div class="stars-cross-aux" ref="starsCrossAuxContainer"></div>
    </div>
</template>

<style>
@reference "@/css/app.css";

.sky {
    @apply fixed h-full w-full overflow-hidden;
    z-index: -1;
    background:
        radial-gradient(at 51% 46%, #041028 0, transparent 50%), radial-gradient(at 85% 99%, #330509 0, transparent 50%),
        radial-gradient(at 18% 22%, #111b4f 0, transparent 50%), #041028;
    transform: scale(1);
}

.mountains {
    position: absolute;
    height: 80px;
    width: 100%;
    bottom: 0;
    left: 0;
    z-index: 2;
}

.light-base {
    position: absolute;
    bottom: -80px;
    left: 10vw;
    width: 80vw;
    height: 100px;
    border-radius: 50%;
    box-shadow:
        -0px -10px 30px 10px #ffc1a0,
        -0px -20px 40px 20px #fe9c7f,
        -0px -30px 60px 30px #be6590,
        -0px -50px 150px 75px #632b6c;
    background-color: #ffc1a0;
    z-index: 1;
}

.sky-base {
    content: '';
    background: linear-gradient(to bottom, rgba(55, 5, 105, 0) 0%, rgba(9, 0, 22, 1) 100%);
    width: 100%;
    height: 100px;
    position: absolute;
    bottom: 0;
    z-index: 3;
}

.mountains > [class^='mountain'],
.mountains > [class^='mountain']:after,
.mountains > [class^='mountain']:before,
.mountains > [class^='land'],
.mountains > [class^='land']:after,
.mountains > [class^='land']:before {
    background: #110124;
    box-shadow: 0 0 50px 5px rgba(255, 255, 255, 0.2);
    position: absolute;
    bottom: 0;
}

.mountain-2 {
    width: 60px;
    height: 60px;
    bottom: -20px !important;
    left: -10px;
    transform: rotate(45deg);
    border-top-left-radius: 10px;
}

.mountain-1 {
    width: 100px;
    height: 100px;
    bottom: -40px !important;
    left: 10px;
    transform: rotate(45deg);
    border-top-left-radius: 10px;
}

.land-1 {
    width: 30%;
    height: 20px;
    border-top-right-radius: 100%;
}

.land-2 {
    width: 60%;
    height: 15px;
    left: 30%;
    border-top-left-radius: 200%;
    border-top-right-radius: 200%;
}

.land-3 {
    width: 20%;
    height: 20px;
    left: 80%;
    border-top-left-radius: 100%;
}

.stars {
    position: absolute;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    transition: all 10s linear;
}

.star {
    position: absolute;
    border-radius: 50%;
    background-color: white;
    opacity: 0.8;
}

.blink {
    animation: blink ease-in-out infinite;
}

@keyframes blink {
    50% {
        opacity: 0;
    }
}

.star-0 {
    height: 0.5px;
    width: 0.5px;
}

.star-1 {
    height: 1px;
    width: 1px;
}

.star-2 {
    height: 1.5px;
    width: 1.5px;
}

.star-3 {
    height: 2px;
    width: 2px;
}

.star-4 {
    height: 2.5px;
    width: 2.5px;
    box-shadow: 0 0 6px 1px rgba(255, 255, 255, 0.5);
}

.star-5 {
    height: 2.5px;
    width: 2.5px;
    box-shadow: 0 0 6px 1px rgba(255, 255, 255, 0.7);
}

.stars-cross {
    position: absolute;
    top: 10vh;
    left: 0;
    width: 120vw;
    height: 20vh;
    transform: rotate(20deg);
    transform-origin: top left;
}

.stars-cross-aux {
    position: absolute;
    top: 0vh;
    left: 10vw;
    width: 120vw;
    height: 10vh;
    transform: rotate(20deg);
    transform-origin: top left;
}

.stars-cross > .blur,
.stars-cross-aux > .blur {
    position: absolute;
    border-radius: 50%;
    background-color: white;
    opacity: 1;
    filter: blur(15px);
    width: 5px;
    height: 10px;
}
</style>
