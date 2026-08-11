<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import type { Form, FormSubmitEvent } from '@nuxt/ui';
import { computed, onMounted, onUnmounted, reactive, ref, useTemplateRef } from 'vue';
import { z } from 'zod';

const turnstileSitekey = import.meta.env.VITE_TURNSTILE_SITE_KEY || '';

const schema = z.object({
    email: z.email('Please enter a valid email address.'),
    subject: z.string().trim().min(1, 'Subject is required.').max(255, 'Subject must be less than 255 characters.'),
    message: z.string().trim().min(10, 'Message must be at least 10 characters long.'),
});
type Schema = z.output<typeof schema>;

const state = reactive<Partial<Schema>>({ email: '', subject: '', message: '' });
const formRef = useTemplateRef<Form<typeof schema>>('formRef');

const isLoading = ref(false);
const submitError = ref('');
const turnstileToken = ref('');
const turnstileWidgetId = ref<string | null>(null);

const { isOpen, closeModal } = useModal();
const open = computed({
    get: () => isOpen('contact-modal'),
    set: (value: boolean) => {
        if (!value) closeModal('contact-modal');
    },
});

const toast = useToast();

function onLoadTurnstile() {
    turnstileWidgetId.value = turnstile.render('#turnstile-container', {
        sitekey: turnstileSitekey,
        callback: function (token: string) {
            turnstileToken.value = token;
        },
    });
}

onMounted(() => {
    try {
        onLoadTurnstile();
    } catch {
        submitError.value = 'Failed to load security verification. Please refresh the page.';
    }
});

onUnmounted(() => {
    if (turnstileWidgetId.value && window.turnstile) {
        try {
            window.turnstile.remove(turnstileWidgetId.value);
        } catch {
            submitError.value = 'Failed to clean up security verification. Please refresh the page.';
        }
    }
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
    submitError.value = '';

    if (!turnstileToken.value) {
        submitError.value = 'Please complete the security verification.';
        return;
    }

    isLoading.value = true;

    try {
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                ...event.data,
                turnstile_token: turnstileToken.value,
            }),
        });

        const data = (await response.json()) as { message?: string; errors?: Record<string, string[]> };

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                formRef.value?.setErrors(
                    Object.entries(data.errors).flatMap(([name, messages]) => messages.map((message) => ({ name, message }))),
                );
            } else {
                submitError.value = data.message || 'Failed to send message. Please try again.';
            }
            return;
        }

        toast.add({ title: data.message || 'Message sent successfully!', color: 'success' });
        open.value = false;
    } catch (error: unknown) {
        submitError.value = error instanceof Error ? error.message : 'Failed to send message. Please try again.';
    } finally {
        isLoading.value = false;
    }
}
</script>

<template>
    <UModal v-model:open="open" title="Contact Me">
        <template #body>
            <UAlert v-if="submitError" color="error" variant="subtle" :title="submitError" class="mb-4" />

            <UForm id="contact-form" ref="formRef" :schema="schema" :state="state" class="flex flex-col gap-4" @submit="onSubmit">
                <UFormField label="Email" name="email">
                    <UInput v-model="state.email" type="email" placeholder="your.email@example.com" autocomplete="email" class="w-full" />
                </UFormField>

                <UFormField label="Subject" name="subject">
                    <UInput v-model="state.subject" placeholder="Tacos are delicious!" autocomplete="off" class="w-full" />
                </UFormField>

                <UFormField label="Message" name="message">
                    <UTextarea v-model="state.message" placeholder="Your message here..." :rows="4" class="w-full" />
                </UFormField>
            </UForm>

            <div class="flex justify-center pt-4">
                <div class="scale-80 md:scale-100" id="turnstile-container"></div>
            </div>
        </template>

        <template #footer="{ close }">
            <UButton color="neutral" variant="outline" label="Cancel" @click="close" />
            <UButton type="submit" form="contact-form" label="Send Message" :loading="isLoading" />
        </template>
    </UModal>
</template>
