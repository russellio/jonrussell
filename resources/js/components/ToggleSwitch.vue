<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faStar } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref, watch } from 'vue';

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

watch(
    () => props.modelValue,
    (newValue) => {
        internalValue.value = newValue;
    },
);

const emitChange = () => {
    emit('update:modelValue', internalValue.value);
};
</script>

<template>
    <label class="flex cursor-pointer flex-col items-center justify-items-center lg:flex-row">
        <span v-if="label" class="me-2 text-terminal-black-50" :class="{ 'font-bold': internalValue }">
            {{ label }}
        </span>
        <div class="relative">
            <input id="toggleSwitchInput" type="checkbox" class="peer sr-only" v-model="internalValue" @change="emitChange" />
            <div class="h-4 w-8 rounded-full bg-gray-300 shadow-inner transition-colors duration-200 ease-in-out peer-checked:bg-blue-500"></div>
            <div
                class="absolute top-0 left-0 h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200 ease-in-out peer-checked:translate-x-full"
            ></div>
        </div>
        <div class="hidden lg:inline-block">
            <FontAwesomeIcon
                :icon="faStar"
                class="ms-2 rotate-12 text-gold opacity-40"
                :class="{ visible: internalValue, collapse: !internalValue }"
                size="lg"
            />
        </div>
    </label>
</template>
