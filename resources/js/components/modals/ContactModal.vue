<script setup lang="ts">
import Modal from '@/js/components/modals/Modal.vue';
import { onMounted, onUnmounted, ref } from 'vue';

const turnstileSitekey = import.meta.env.VITE_TURNSTILE_SITE_KEY || '';

const form = ref({
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

// Validation state
const validationErrors = ref<Record<string, string>>({});
const isFormValid = ref(false);

// Validation functions
const validateEmail = (email: string): string => {
    if (!email.trim()) {
        return 'Email is required.';
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return 'Please enter a valid email address.';
    }
    return '';
};

const validateSubject = (subject: string): string => {
    if (!subject.trim()) {
        return 'Subject is required.';
    }
    if (subject.trim().length < 2) {
        return 'Subject must be at least 2 characters long.';
    }
    if (subject.trim().length > 100) {
        return 'Subject must be less than 100 characters.';
    }
    return '';
};

const validateMessage = (message: string): string => {
    if (!message.trim()) {
        return 'Message is required.';
    }
    if (message.trim().length < 10) {
        return 'Message must be at least 10 characters long.';
    }
    if (message.trim().length > 1000) {
        return 'Message must be less than 1000 characters.';
    }
    return '';
};

const validateField = (field: string, value: string) => {
    let error = '';
    switch (field) {
        case 'email':
            error = validateEmail(value);
            break;
        case 'subject':
            error = validateSubject(value);
            break;
        case 'message':
            error = validateMessage(value);
            break;
    }

    if (error) {
        validationErrors.value[field] = error;
    } else {
        delete validationErrors.value[field];
    }

    updateFormValidity();
};

const updateFormValidity = () => {
    const hasErrors = Object.keys(validationErrors.value).length > 0;
    const hasEmptyFields = !form.value.email.trim() || !form.value.subject.trim() || !form.value.message.trim();
    isFormValid.value = !hasErrors && !hasEmptyFields;
};

const handleEmailChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    form.value.email = target.value;
    validateField('email', target.value);
};

const handleSubjectChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    form.value.subject = target.value;
    validateField('subject', target.value);
};

const handleMessageChange = (event: Event) => {
    const target = event.target as HTMLTextAreaElement;
    form.value.message = target.value;
    validateField('message', target.value);
};

function onLoadTurnstile() {
    turnstileWidgetId.value = turnstile.render('#turnstile-container', {
        sitekey: turnstileSitekey,
        callback: function (token: string) {
            console.log('Turnstile challenge completed:', token);
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
    form.value = { email: '', subject: '', message: '' };
    errors.value = {};
    validationErrors.value = {};
    successMessage.value = '';
    turnstileToken.value = '';
    isFormValid.value = false;
    isFormSubmitted.value = false;

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

    validateField('email', form.value.email);
    validateField('subject', form.value.subject);
    validateField('message', form.value.message);

    if (!isFormValid.value) {
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
                ...form.value,
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

        form.value = { email: '', subject: '', message: '' };
        successMessage.value = data.message || 'Message sent successfully!';
        isFormSubmitted.value = true;
    } catch (error: any) {
        console.warn('Error submitting form:', error);
        if (!errors.value || Object.keys(errors.value).length === 0) {
            errors.value.general = error?.message || 'Failed to send message. Please try again.';
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
        :submitDisabled="!isFormValid || isFormSubmitted"
        :submitText="`Send <span class='hidden sm:inline-block'> Message</span>`"
        :isLoading="isLoading"
        :cancelText="!isFormSubmitted ? `Cancel` : `Close`"
        @submit="submitForm"
    >
        <!-- Contact Modal -->
        <div class="flex w-full flex-col gap-4 p-6">
            <div v-if="successMessage" class="success">
                {{ successMessage }}
            </div>

            <div v-for="(error, key) in errors" :key="key" class="error">
                <span v-if="error">{{ key }} {{ error }}</span>
            </div>

            <div v-if="!isFormSubmitted" class="form-wrapper">
                <div>
                    <label for="email">Email:</label>
                    <input
                        id="email"
                        :value="form.email"
                        @change="handleEmailChange"
                        type="email"
                        placeholder="your.email@example.com"
                        :class="{
                            'border-red-500': errors.email || validationErrors.email,
                            'border-success': form.email && !validationErrors.email && !errors.email,
                        }"
                    />
                    <div v-if="errors.email" class="error">{{ errors.email[0] }}</div>
                    <div v-if="validationErrors.email" class="error">{{ validationErrors.email }}</div>
                </div>

                <div>
                    <label for="subject">Subject:</label>
                    <input
                        id="subject"
                        :value="form.subject"
                        @change="handleSubjectChange"
                        type="text"
                        placeholder="Hey!!!"
                        :class="{
                            'border-red-500': errors.subject || validationErrors.subject,
                            'border-success': form.subject && !validationErrors.subject && !errors.subject,
                        }"
                    />
                    <div v-if="errors.subject" class="error">{{ errors.subject[0] }}</div>
                    <div v-if="validationErrors.subject" class="error">{{ validationErrors.subject }}</div>
                </div>

                <div>
                    <label for="message">Message:</label>
                    <textarea
                        id="message"
                        :value="form.message"
                        @change="handleMessageChange"
                        placeholder="Your message here..."
                        rows="4"
                        :class="{
                            'border-error': errors.message || validationErrors.message,
                            'border-success': form.message && !validationErrors.message && !errors.message,
                        }"
                    ></textarea>
                    <div v-if="errors.message" class="error">{{ errors.message[0] }}</div>
                    <div v-if="validationErrors.message" class="error">{{ validationErrors.message }}</div>
                </div>

                <div class="flex justify-center">
                    <div class="scale-80 md:scale-100" id="turnstile-container"></div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
@reference "@/css/app.css";

label {
    @apply mt-3 mb-2 block font-space-mono text-xl font-bold text-white;
}

input,
textarea {
    @apply font-mono;
    @apply w-full rounded-md border bg-white px-3 py-2 text-lg text-terminal-black;
    @apply placeholder:text-terminal-black-400 placeholder:italic;
    @apply focus:border-blue focus:shadow focus:outline-none;
}
</style>
