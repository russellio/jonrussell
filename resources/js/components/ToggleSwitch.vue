<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
    name: "ToggleSwitch",
    props: {
        modelValue: {
            type: Boolean,
            default: false,
        },
        label: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            internalValue: this.modelValue,
        };
    },
    watch: {
        modelValue(newValue) {
            this.internalValue = newValue;
        },
    },
    methods: {
        emitChange() {
            this.$emit('update:modelValue', this.internalValue);
        },
    },
})
</script>

<template>
    <label class="flex items-center cursor-pointer">
        <div class="relative">
            <input
                type="checkbox"
                class="sr-only peer"
                v-model="internalValue"
                @change="emitChange"
            />
            <div
                class="w-10 h-6 bg-gray-300 rounded-full shadow-inner peer-checked:bg-blue-500 transition-colors duration-200 ease-in-out"
            ></div>
            <div
                class="absolute left-0 top-0 w-6 h-6 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out peer-checked:translate-x-full"
            ></div>
        </div>
        <span v-if="label" class="ml-3 text-gray-700">{{ label }}</span>
    </label>
</template>

<style scoped>

</style>
