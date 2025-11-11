import type { Config } from 'tailwindcss'

export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.ts',
    './resources/**/*.vue',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      animation: {
        'infinite-scroll': 'infinite-scroll linear infinite',
      },
      fontFamily: {
        'space-mono': ['Space Mono', 'monospace'],
        'sixtyfour': ['Sixtyfour', 'monospace'],
      },
      colors: {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        success: 'var(--color-success)',
        error: 'var(--color-error)',
        blue: 'var(--color-blue)',
        'dark-blue': 'var(--color-dark-blue)',
        content: 'var(--color-content)',
        gold: 'var(--color-gold)',
        'bright-green': 'var(--color-bright-green)',
        red: 'var(--color-red)',
        'terminal-black': 'var(--color-terminal-black)',
        'bulldog-red': 'var(--color-bulldog-red)',
      },
      screens: {},
      keyframes: {
        'infinite-scroll': {
          '0%': { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-50%)' },
        }
      }
    },
  },
} satisfies Config

