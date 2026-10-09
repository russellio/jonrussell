import AppLayout from '@/js/layout/AppLayout.vue';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import ui from '@nuxt/ui/vue-plugin';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import { createSSRApp, DefineComponent, h } from 'vue';
import { renderToString } from 'vue/server-renderer';

const appName = import.meta.env.VITE_APP_NAME || 'Jon Russell - Senior Software Engineer';

createServer(
    (page) =>
        createInertiaApp({
            page,
            render: renderToString,
            title: (title) => title || appName,
            resolve: resolvePage,
            setup: ({ App, props, plugin }) =>
                createSSRApp({ render: () => h(App, props) })
                    .use(createPinia())
                    .use(plugin)
                    .use(ui),
        }),
    // Multiple worker processes behind round-robin: one render that wedges the
    // event loop (the repeated 30s cURL timeouts in Sentry) now takes down one
    // worker instead of the only SSR process for the whole site.
    { cluster: true },
);

async function resolvePage(name: string) {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

    const page = await resolvePageComponent<DefineComponent>(`./pages/${name}.vue`, pages);
    page.default.layout ??= AppLayout;
    return page;
}
