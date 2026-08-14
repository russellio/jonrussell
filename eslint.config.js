import prettier from 'eslint-config-prettier';
import vue from 'eslint-plugin-vue';

import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: ['vendor', 'node_modules/**', 'public', 'bootstrap/ssr', 'tailwind.config.js', 'resources/js/components/ui/*', '.remember/**', '.claude/**'],
    },
    {
      linterOptions: { reportUnusedDisableDirectives: 'warn' },
    },

    {
      rules: {
        'vue/multi-word-component-names': 'off',
        // '@typescript-eslint/no-explicit-any': 'off',
        // Pre-existing codebase uses `any` — warn rather than block CI
        '@typescript-eslint/no-explicit-any': 'warn',
        // Many pre-existing unused imports/vars — warn while team cleans up
        '@typescript-eslint/no-unused-vars': [
          'warn',
          {
            argsIgnorePattern: '^_',
            varsIgnorePattern: '^_',
            caughtErrorsIgnorePattern: '^_',
          },
        ],
        // React Native requires require() for static assets (images, fonts)
        // '@typescript-eslint/no-require-imports': 'warn',
      },
    },
    prettier,
);
