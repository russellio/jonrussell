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
    // Single process on purpose. Inertia's cluster mode forks
    // os.availableParallelism() workers, and the production droplet has 1 vCPU --
    // so clustering buys no parallelism there, while the cluster primary has no
    // cluster.on('exit') respawn. A dead worker would leave the primary alive
    // holding the port with nothing to serve, which also keeps systemd's
    // Restart=always from ever firing. The 3s inertia.ssr.timeout is what bounds a
    // wedged render: the request degrades to client-side rendering instead of
    // hanging. Revisit only if this ever runs on a multi-core host.
    { cluster: false },
);

async function resolvePage(name: string) {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

    const page = await resolvePageComponent<DefineComponent>(`./pages/${name}.vue`, pages);
    page.default.layout ??= AppLayout;
    return page;
}
