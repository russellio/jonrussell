import '@/css/app.css';
import AppLayout from '@/js/layout/AppLayout.vue';
import { createInertiaApp } from '@inertiajs/vue3';
import ui from '@nuxt/ui/vue-plugin';
import '@russellio/vue-background-stars/style.css';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import * as Sentry from '@sentry/vue';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createSSRApp, h } from 'vue';
import { createGtag } from 'vue-gtag';

const appName = import.meta.env.VITE_APP_NAME || 'Jon Russell - Senior Full Stack Engineer';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const page = await resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue'));
        page.default.layout ??= AppLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createSSRApp({ render: () => h(App, props) })
            .use(pinia)
            .use(plugin)
            .use(ui)
            .use(
                createGtag({
                    tagId: 'G-Z1V3TF6W15',
                }),
            );

        Sentry.init({
            app,
            dsn: import.meta.env.VITE_SENTRY_DSN,
            sendDefaultPii: false,
            integrations: [Sentry.browserTracingIntegration()],
            tracesSampleRate: parseFloat(import.meta.env.VITE_SENTRY_TRACES_SAMPLE_RATE ?? '0.8'),
            tracePropagationTargets: ['localhost', /^https:\/\/(?:[\w-]+\.)?jonrussell\.dev(?:[/:]|$)/],
        });

        app.mount(el);
    },
});
