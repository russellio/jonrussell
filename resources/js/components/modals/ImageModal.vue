<script setup lang="ts">
import type { Image } from '@/js/types';

defineProps<{ image: Image | null }>();
const open = defineModel<boolean>('open', { default: false });
</script>

<template>
    <UModal v-model:open="open" :ui="{ content: 'max-w-3xl' }" :title="image?.title || 'Project image'">
        <template #body>
            <img v-if="image?.src" :src="`/images/projects/${image.src}`" :title="image.title" :alt="image.alt" class="w-full rounded" />
        </template>
    </UModal>
</template>
