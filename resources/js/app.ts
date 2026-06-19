import '@/css/app.css';
import '@/js/lib/icons';
import { createInertiaApp } from '@inertiajs/vue3';
import '@russellio/vue-background-stars/style.css';
import * as Sentry from '@sentry/vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Jon Russell - Senior Software Engineer';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) })
            .use(pinia)
            .use(plugin);

        Sentry.init({
            app,
            dsn: import.meta.env.VITE_SENTRY_DSN,
            sendDefaultPii: false,
            integrations: [Sentry.browserTracingIntegration()],
            tracesSampleRate: parseFloat(import.meta.env.VITE_SENTRY_TRACES_SAMPLE_RATE ?? '0.8'),
            tracePropagationTargets: ['localhost', /^https:\/\/jonrussell\.*/],
        });

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
