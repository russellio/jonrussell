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
        burgundy: 'var(--color-burgundy)',
        'terminal-black': 'var(--color-terminal-black)',
      },
    },
  },
  plugins: [],
} satisfies Config

