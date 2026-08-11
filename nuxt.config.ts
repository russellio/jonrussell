export default defineNuxtConfig({
    modules: ['@nuxt/eslint', '@nuxt/ui'],

    devtools: {
        enabled: true,
    },

    css: ['~/resources/css/app.css'],

    icon: {
        clientBundle: {
            scan: true,
        },
    },

    routeRules: {
        '/': { prerender: true },
    },

    compatibilityDate: '2026-06-30',

    eslint: {
        config: {
            stylistic: {
                commaDangle: 'never',
                braceStyle: '1tbs',
            },
        },
    },
});
