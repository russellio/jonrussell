<script setup lang="ts">
import { cn } from '@/js/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div
        :class="
            cn(
                'rounded-br-2xl rounded-bl-2xl border border-t-0 border-s-white/10 border-e-white/25 border-b-brand-red bg-black/30 ps-4 pe-2 pt-2 pb-4',
                props.class,
            )
        "
    >
        <slot />
    </div>
</template>
