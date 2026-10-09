import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import ui from '@nuxt/ui/vite';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ui({
            router: 'inertia', // Inertia compat layer for `to` props on UButton/ULink
            colorMode: false, // dark-only; avoids @vueuse/core localStorage SSR hydration mismatch
            ui: { colors: { primary: 'blue', neutral: 'slate' } },
            icon: {
                clientBundle: {
                    scan: true,
                    // Template-literal icon names (`i-${iconType}-${iconName}`) that `scan: true` can't see statically;
                    // add new DB-driven tech-stack icons here too, or they fall back to a live Iconify CDN fetch.
                    icons: [
                        'simple-icons:laravel',
                        'simple-icons:php',
                        'simple-icons:vuedotjs',
                        'simple-icons:javascript',
                        'simple-icons:html5',
                        'simple-icons:css3',
                        'simple-icons:mysql',
                        'simple-icons:typescript',
                        'simple-icons:react',
                        'simple-icons:python',
                        'simple-icons:expo',
                        'simple-icons:googlemaps',
                        'simple-icons:firebase',
                        'simple-icons:sentry',
                        'simple-icons:appstore',
                        'simple-icons:revenuecat',
                        'simple-icons:jest',
                        'simple-icons:github',
                        'simple-icons:composer',
                        'simple-icons:pinia',
                        'simple-icons:jquery',
                        'simple-icons:webpack',
                        'simple-icons:vite',
                        'simple-icons:bootstrap',
                        'simple-icons:tailwindcss',
                        'simple-icons:json',
                        'simple-icons:xml',
                        'simple-icons:git',
                        'simple-icons:jira',
                        'simple-icons:linux',
                        'simple-icons:docker',
                        'simple-icons:jenkins',
                        'simple-icons:azurepipelines',
                        'simple-icons:scrumalliance',
                        'simple-icons:phpstorm',
                        'simple-icons:visualstudiocode',
                        'simple-icons:cursor',
                        'simple-icons:claude',
                        'simple-icons:jsonwebtokens',
                        'simple-icons:redis',
                        'simple-icons:microsoftsqlserver',
                        'simple-icons:npm',
                        'simple-icons:yarn',
                        'simple-icons:postman',
                        'simple-icons:pivotaltracker',
                        'simple-icons:bitbucket',
                        'simple-icons:lumen',
                        // Nuxt UI component defaults resolved from `appConfig.ui.icons` (inside node_modules,
                        // so the scanner never sees them) — e.g. UCarousel's prev/next arrows.
                        'lucide:arrow-left',
                        'lucide:arrow-right',
                        'lucide:code',
                        'lucide:flask-conical',
                        'lucide:workflow',
                        'lucide:layers',
                        'lucide:map-pin',
                        'lucide:user-shield',
                        'lucide:github',
                        'lucide:linkedin',
                    ],
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources'),
        },
    },
    ssr: {
        // Bundle every dependency into the SSR output instead of leaving bare imports
        // for Node to resolve at runtime. Two reasons, both load-bearing:
        //
        // 1. Externalized packages skip @nuxt/ui's unplugin-auto-import transform,
        //    which is what resolves the '#imports' virtual specifier its composables
        //    (e.g. useComponentIcons) rely on — Node can't resolve '#imports' natively.
        // 2. The deploy ships only public/build + bootstrap/ssr and never installs node
        //    modules, so anything left external resolves against whatever happens to be
        //    in the server's node_modules. That drifted six weeks behind the lockfile
        //    and left SSR one `pnpm install` away from breaking: v1.4.0 of
        //    @russellio/vue-background-stars has a bare `import './...css'` in its ESM
        //    entry, which Node cannot load, so the Home chunk's dynamic import rejects
        //    and renderToString never resolves — HTTP 200 with a zero-byte body.
        //
        // Bundling everything makes the SSR output self-contained, so the server's
        // node_modules stops being load-bearing for rendering.
        noExternal: true,
    },
});
