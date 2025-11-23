<script setup lang="ts">
import Modal from '@/js/components/modals/Modal.vue';
import type { ValidationRule } from '@vuelidate/core';
import { useVuelidate } from '@vuelidate/core';
import { email } from '@vuelidate/validators';
import { onMounted, onUnmounted, reactive, ref } from 'vue';

const turnstileSitekey = import.meta.env.VITE_TURNSTILE_SITE_KEY || '';

const form = reactive({
    email: '',
    subject: '',
    message: '',
});

const isLoading = ref(false);
const errors = ref<Record<string, any>>({});
const successMessage = ref('');
const turnstileToken = ref('');
const turnstileWidgetId = ref<string | null>(null);
const isFormSubmitted = ref(false);

// Custom validators for trim-based validation
const trimmedRequired = (value: string): boolean => {
    return !!value?.trim();
};

const trimmedMinLength = (min: number): ValidationRule => {
    return (value: string): boolean => {
        return !value || value.trim().length >= min;
    };
};

const trimmedMaxLength = (max: number): ValidationRule => {
    return (value: string): boolean => {
        return !value || value.trim().length <= max;
    };
};

// Validation rules
const rules = {
    email: {
        required: { $validator: trimmedRequired, $message: 'Email is required.' },
        email: { $validator: email, $message: 'Please enter a valid email address.' },
    },
    subject: {
        required: { $validator: trimmedRequired, $message: 'Subject is required.' },
        minLength: { $validator: trimmedMinLength(2), $message: 'Subject must be at least 2 characters long.' },
        maxLength: { $validator: trimmedMaxLength(100), $message: 'Subject must be less than 100 characters.' },
    },
    message: {
        required: { $validator: trimmedRequired, $message: 'Message is required.' },
        minLength: { $validator: trimmedMinLength(10), $message: 'Message must be at least 10 characters long.' },
        maxLength: { $validator: trimmedMaxLength(1000), $message: 'Message must be less than 1000 characters.' },
    },
};

const v$ = useVuelidate(rules, form);

function onLoadTurnstile() {
    turnstileWidgetId.value = turnstile.render('#turnstile-container', {
        sitekey: turnstileSitekey,
        callback: (token: string) => {
            turnstileToken.value = token;
        },
    });
}

onMounted(() => {
    resetForm();
});

onUnmounted(() => {
    if (turnstileWidgetId.value && window.turnstile) {
        try {
            window.turnstile.remove(turnstileWidgetId.value);
        } catch (error) {
            console.warn('Failed to remove Turnstile widget:', error);
        }
    }
});

const resetForm = async () => {
    form.email = '';
    form.subject = '';
    form.message = '';
    errors.value = {};
    successMessage.value = '';
    turnstileToken.value = '';
    isFormSubmitted.value = false;
    v$.value.$reset();

    try {
        onLoadTurnstile();
    } catch (error) {
        console.warn('Failed to load Turnstile:', error);
        errors.value.turnstile = 'Failed to load security verification. Please refresh the page.';
    }
};

const submitForm = async () => {
    isLoading.value = true;
    errors.value = {};
    successMessage.value = '';

    const isFormValid = await v$.value.$validate();

    if (!isFormValid) {
        errors.value.validation = 'Please fix the errors above before submitting.';
        isLoading.value = false;
        return;
    }

    if (!turnstileToken.value) {
        errors.value.turnstile = 'Please complete the security verification.';
        isLoading.value = false;
        return;
    }

    try {
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                ...form,
                turnstile_token: turnstileToken.value,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                errors.value = data.errors || {};
            }
            throw new Error(data.message || 'Failed to send message');
        }

        form.email = '';
        form.subject = '';
        form.message = '';
        successMessage.value = data.message || 'Message sent successfully!';
        isFormSubmitted.value = true;
    } catch (error: unknown) {
        console.warn('Error submitting form:', error);
        const errorMessage = error instanceof Error ? error.message : 'Failed to send message. Please try again.';
        if (!errors.value || Object.keys(errors.value).length === 0) {
            errors.value.general = errorMessage;
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Modal
        modalId="contact-modal"
        title="Contact Me"
        :showSubmit="!isFormSubmitted"
        :submitDisabled="v$.$invalid || isFormSubmitted"
        :submitText="`Send <span class='hidden sm:inline-block'> Message</span>`"
        :isLoading="isLoading"
        :cancelText="!isFormSubmitted ? `Cancel` : `Close`"
        @submit="submitForm"
    >
        <!-- Contact Modal -->
        <div class="flex w-full flex-col">
            <div v-if="successMessage" class="success">
                {{ successMessage }}
            </div>

            <div v-for="(error, key) in errors" :key="key" class="error">
                <span v-if="error">{{ key }} {{ error }}</span>
            </div>

            <div v-if="!isFormSubmitted" class="grid w-full grid-cols-1 gap-1 p-4 md:grid-cols-[20%_auto] md:gap-4 md:p-6">
                <label for="email">Email:</label>
                <div class="flex flex-col">
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="your.email@example.com"
                        :class="{
                            'border-red-500': errors.email || v$.email.$error,
                            'border-success': form.email && !v$.email.$error && !errors.email,
                        }"
                        autocomplete="true"
                    />
                    <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>
                    <div v-for="error of v$.email.$errors" :key="error.$uid" class="error">
                        {{ error.$message }}
                    </div>
                </div>

                <label for="subject">Subject:</label>
                <div class="flex flex-col">
                    <input
                        id="subject"
                        v-model="form.subject"
                        type="text"
                        placeholder="Tacos are delicious!"
                        :class="{
                            'border-red-500': errors.subject || v$.subject.$error,
                            'border-success': form.subject && !v$.subject.$error && !errors.subject,
                        }"
                        autocomplete="false"
                    />
                    <div v-if="errors.subject" class="error">{{ errors.subject[0] }}</div>
                    <div v-for="error of v$.subject.$errors" :key="error.$uid" class="error">
                        {{ error.$message }}
                    </div>
                </div>

                <label for="message">Message:</label>
                <div class="flex flex-col">
                    <textarea
                        id="message"
                        v-model="form.message"
                        placeholder="Your message here..."
                        rows="4"
                        :class="{
                            'border-error': errors.message || v$.message.$error,
                            'border-success': form.message && !v$.message.$error && !errors.message,
                        }"
                    ></textarea>
                    <div v-if="errors.message" class="error">{{ errors.message[0] }}</div>
                    <div v-for="error of v$.message.$errors" :key="error.$uid" class="error">
                        {{ error.$message }}
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="scale-80 md:scale-100" id="turnstile-container"></div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
@reference "@/css/app.css";

label {
    @apply mt-3 mb-2 font-space-mono text-xl font-semibold tracking-widest text-white;
    @apply md:mt-1 md:text-end md:text-lg lg:text-2xl;
}

input,
textarea {
    @apply font-sans;
    @apply w-full rounded-md border bg-white px-3 py-2 text-lg text-terminal-black;
    @apply placeholder:text-terminal-black/40 placeholder:italic;
    @apply focus:border-blue focus:shadow focus:outline-none;
}
</style>
