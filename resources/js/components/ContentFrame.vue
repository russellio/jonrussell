<script setup lang="ts"></script>

<template>
    <div class="content-wrapper">
        <div class="content-top">
            <svg data-name="Content Top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" class="shape-fill"></path>
            </svg>
        </div>

        <slot />

        <div class="content-bottom">
            <svg data-name="Content Bottom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" class="shape-fill"></path>
            </svg>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.content-wrapper {
    @apply my-10 max-w-7xl px-4 sm:px-6 lg:px-8;
    @apply relative mx-auto w-9/10 rounded-xl bg-content pt-12 pb-16;
}

.content-top,
.content-bottom {
    @apply absolute left-0 w-full overflow-hidden rounded-b-xl;
    line-height: 0;
    border-bottom: 3px ridge var(--color-secondary);

    svg {
        position: relative;
        display: block;
        width: 115%;
        height: 70px;
    }

    .shape-fill {
        fill: #041028;
    }
}

.content-top {
    @apply top-0;
    transform: rotate(180deg);
}

.content-bottom {
    @apply bottom-0 mt-40;
    transform: scaleX(-1);

    svg {
        height: 55px;
    }
}
</style>
