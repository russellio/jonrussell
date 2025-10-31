<script setup lang="ts">
import { ref, watch } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faStar } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faStar);

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: '',
    },
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const internalValue = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    internalValue.value = newValue;
});

const emitChange = () => {
    emit('update:modelValue', internalValue.value);
};
</script>

<template>
    <label class="flex flex-col lg:flex-row items-center justify-items-center cursor-pointer">
        <span v-if="label" class="text-terminal-black-50 me-2" :class="{'font-bold': internalValue}">
            {{ label }}
        </span>
        <div class="relative">
            <input
                type="checkbox"
                class="sr-only peer"
                v-model="internalValue"
                @change="emitChange"
            />
            <div
                class="w-8 h-4 bg-gray-300 rounded-full shadow-inner peer-checked:bg-blue-500 transition-colors duration-200 ease-in-out"
            ></div>
            <div
                class="absolute left-0 top-0 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out peer-checked:translate-x-full"
            ></div>
        </div>
        <div class="hidden lg:inline-block">
            <FontAwesomeIcon :icon="faStar" class="ms-2 text-gold opacity-40 rotate-12" :class="{ 'visible': internalValue, 'collapse': !internalValue }" size="lg" />
        </div>
    </label>
</template>

<style scoped>

</style>
