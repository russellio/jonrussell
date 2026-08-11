<script setup lang="ts">
defineProps<{
    loading: boolean;
    error: string | null;
}>();

defineEmits<{
    retry: [];
}>();
</script>

<template>
    <div v-if="loading">
        <slot name="loading">
            <div class="space-y-3 py-8">
                <USkeleton class="h-4 w-1/3" />
                <USkeleton class="h-4 w-2/3" />
                <USkeleton class="h-4 w-1/2" />
            </div>
        </slot>
    </div>

    <UAlert v-else-if="error" color="error" variant="subtle" icon="i-lucide-triangle-alert" :title="error" class="my-4">
        <template #actions>
            <UButton color="neutral" variant="outline" size="xs" icon="i-lucide-rotate-cw" label="Retry" @click="$emit('retry')" />
        </template>
    </UAlert>

    <slot v-else />
</template>
