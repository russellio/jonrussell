import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import * as Sentry from "@sentry/vue";
import '@/css/app.css';

const appName = import.meta.env.VITE_APP_NAME || 'Jon Russell - Senior Software Engineer';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
      resolvePageComponent(
        `./pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./pages/**/*.vue')
      ),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) })
            .use(pinia)
            .use(plugin);

        Sentry.init({
          app,
          dsn: "https://8c80a6050e1ee8cc8bdad1e24ab7b0a7@o4509299183583232.ingest.us.sentry.io/4510304183451648",
          // Setting this option to true will send default PII data to Sentry.
          // For example, automatic IP address collection on events
          sendDefaultPii: true,
          integrations: [Sentry.browserTracingIntegration()],
          tracesSampleRate: 1.0,
          tracePropagationTargets: ["localhost", /^https:\/\/jonrussell\.*/],
        });

        app.mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});
