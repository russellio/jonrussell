
import { defineConfig } from 'vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import ui from '@nuxt/ui/vite';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

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
            ui: { colors: { primary: 'teal', neutral: 'slate' } },
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
                        'lucide:code',
                        'lucide:flask-conical',
                        'lucide:workflow',
                        'lucide:layers',
                    ],
                },
            }, // the one real intent of nuxt.config.ts
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
});
