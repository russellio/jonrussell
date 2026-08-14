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
            title: (title) => (title ? `${title} - ${appName}` : appName),
            resolve: resolvePage,
            setup: ({ App, props, plugin }) =>
                createSSRApp({ render: () => h(App, props) })
                    .use(createPinia())
                    .use(plugin)
                    .use(ui),
        }),
    { cluster: false },
);

async function resolvePage(name: string) {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

    const page = await resolvePageComponent<DefineComponent>(`./pages/${name}.vue`, pages);
    page.default.layout ??= AppLayout;
    return page;
}
