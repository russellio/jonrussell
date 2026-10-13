<script setup lang="ts">
import { useModal } from '@/js/composables/useModal';
import type { Form, FormSubmitEvent } from '@nuxt/ui';
import { useToast } from '@nuxt/ui/composables';
import { computed, onUnmounted, reactive, ref, useTemplateRef, watch } from 'vue';
import { z } from 'zod';

const turnstileSitekey = import.meta.env.VITE_TURNSTILE_SITE_KEY || '';

// The field name the server validates the token under, and therefore the key its errors
// come back on. It has no UFormField of its own.
const TURNSTILE_FIELD = 'turnstile_token';

// Turnstile's api.js used to be a <script> in app.blade.php, which cost every visitor a
// third-party connection and ~80KB for a widget only the contact form uses. It is fetched
// here instead, the first time the form is actually on screen. Module scope (not component
// scope) so re-opening the modal reuses the already-resolved promise.
let turnstileScript: Promise<TurnstileInstance> | null = null;

function loadTurnstile(): Promise<TurnstileInstance> {
    if (window.turnstile) return Promise.resolve(window.turnstile);

    // `onload` is Turnstile's own ready signal — the script's load event can fire before the
    // API object is assigned, which is the race that broke this widget on a previous deploy.
    turnstileScript ??= new Promise<TurnstileInstance>((resolve, reject) => {
        const callbackName = '__onTurnstileReady';
        window[callbackName] = () => resolve(window.turnstile);

        const script = document.createElement('script');
        script.src = `https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=${callbackName}`;
        script.async = true;
        script.onerror = () => {
            turnstileScript = null;
            script.remove();
            reject(new Error('Failed to load the Turnstile script.'));
        };

        document.head.appendChild(script);
    });

    return turnstileScript;
}

const schema = z.object({
    email: z.email('Please enter a valid email address.'),
    subject: z.string().trim().min(1, 'Subject is required.').max(255, 'Subject must be less than 255 characters.'),
    message: z.string().trim().min(10, 'Message must be at least 10 characters long.'),
});
type Schema = z.output<typeof schema>;

const state = reactive<Partial<Schema>>({ email: '', subject: '', message: '' });
const formRef = useTemplateRef<Form<Schema>>('formRef');

const isLoading = ref(false);
const submitError = ref('');
const turnstileToken = ref('');
const turnstileWidgetId = ref<string | null>(null);
const turnstileContainerRef = useTemplateRef<HTMLDivElement>('turnstileContainerRef');

const { isOpen, closeModal } = useModal();
const open = computed({
    get: () => isOpen('contact-modal'),
    set: (value: boolean) => {
        if (!value) closeModal('contact-modal');
    },
});

const toast = useToast();

// UModal teleports/animates its body content in, so the container div isn't
// guaranteed to exist in the DOM yet when this component's onMounted fires.
// Watching the template ref instead reacts to the moment Vue actually mounts it.
watch(
    turnstileContainerRef,
    async (container) => {
        if (!container || turnstileWidgetId.value) return;

        try {
            const turnstile = await loadTurnstile();

            // The modal can close, or another run of this watcher can win, while the script
            // is in flight. Re-check both before rendering into a detached container.
            if (turnstileContainerRef.value !== container || turnstileWidgetId.value) return;

            turnstileWidgetId.value = turnstile.render(container, {
                sitekey: turnstileSitekey,
                callback: (token: string) => {
                    turnstileToken.value = token;
                },
            });
        } catch {
            submitError.value = 'Failed to load security verification. Please refresh the page.';
        }
    },
    { immediate: true },
);

// Turnstile hands out single-use tokens, so a submission that the server refused leaves the
// visitor holding a spent one. Swap it for a fresh challenge before they can try again.
function resetTurnstile() {
    turnstileToken.value = '';

    if (!turnstileWidgetId.value || !window.turnstile) return;

    try {
        window.turnstile.reset(turnstileWidgetId.value);
    } catch {
        // Nothing useful to say here: the real submit error is already on screen, and the
        // server rejects the stale token on the next attempt regardless.
    }
}

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
                [TURNSTILE_FIELD]: turnstileToken.value,
            }),
        });

        const data = (await response.json()) as { message?: string; errors?: Record<string, string[]> };

        if (!response.ok) {
            // The server redeems the token at Cloudflare, and a redeemed token is spent.
            // Without a fresh one, every retry would fail as a duplicate.
            resetTurnstile();

            if (response.status === 422 && data.errors) {
                // `turnstile_token` has no UFormField to attach to, so it would vanish if
                // handed to setErrors. Surface it in the alert above the form instead.
                const errors = Object.entries(data.errors);
                const tokenError = data.errors[TURNSTILE_FIELD]?.[0];

                if (tokenError) submitError.value = tokenError;

                formRef.value?.setErrors(
                    errors.filter(([name]) => name !== TURNSTILE_FIELD).flatMap(([name, messages]) => messages.map((message) => ({ name, message }))),
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
    <UModal v-model:open="open" title="Contact Me" :ui="{ footer: 'justify-end' }">
        <template #body>
            <UAlert v-if="submitError" color="error" variant="subtle" :title="submitError" class="mb-4" />

            <UForm
                id="contact-form"
                ref="formRef"
                :schema="schema"
                :state="state"
                class="flex flex-col gap-4"
                @submit="onSubmit"
                @error="submitError = ''"
            >
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
                <div ref="turnstileContainerRef" class="scale-80 md:scale-100" id="turnstile-container"></div>
            </div>
        </template>

        <template #footer="{ close }">
            <UButton color="neutral" variant="outline" label="Cancel" @click="close" />
            <UButton type="submit" form="contact-form" label="Send Message" :loading="isLoading" class="text-white" />
        </template>
    </UModal>
</template>
