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
        'dark-blue': 'var(--color-dark-blue)',
        content: 'var(--color-content)',
        gold: 'var(--color-gold)',
        'bright-green': 'var(--color-bright-green)',
        red: 'var(--color-red)',
        'terminal-black': 'var(--color-terminal-black)',
        'bulldog-red': 'var(--color-bulldog-red)',

        // blue: {
        //   100: "#d0e0fd",
        //   200: "#a1c1fb",
        //   300: "#73a2f8",
        //   400: "#4483f6",
        //   500: "#1564f4",
        //   600: "#1150c3",
        //   700: "#0d3c92",
        //   800: "#082862",
        //   900: "#041431"
        // },
        // terminalBlack: {
        //   50: "#f3f4f4",
        //   100: "#e7e8e9",
        //   200: "#cdcfd1",
        //   300: "#b6b9bc",
        //   400: "#9ea1a4",
        //   500: "#898c8e",
        //   600: "#737577",
        //   700: "#5f6163",
        //   800: "#4a4b4d",
        //   900: "#38393a",
        //   950: "#2e2f30",
        // },

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

