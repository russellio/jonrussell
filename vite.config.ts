
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
            icon: { clientBundle: { scan: true } }, // the one real intent of nuxt.config.ts
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
