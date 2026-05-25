# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Tech Stack

Full-stack Laravel 13 + Vue 3 application.

- **Backend**: PHP 8.3+, Laravel 13, Eloquent ORM, SQLite (default)
- **Frontend**: Vue 3, Vue Router 5, Pinia, Tailwind CSS 4, TypeScript
- **Build**: Vite 8, Laravel Vite Plugin
- **Testing**: PHPUnit (backend), Vitest (frontend unit), Playwright (E2E)
- **Linting**: Oxlint + ESLint, Prettier

## Commands

### Development

```bash
composer dev        # Full dev stack: PHP server + queue + logs + Vite (recommended)
npm run dev         # Vite dev server only
```

### Build

```bash
npm run build       # Type-check + build
npm run build-only  # Build without type-check
npm run type-check  # TypeScript check via vue-tsc
```

### Testing

```bash
composer test               # PHP tests (clears config cache first)
php artisan test            # PHP tests directly
php artisan test --filter=TestName  # Run a single test
npm run test:unit           # Vitest unit tests
npm run test:e2e            # Playwright E2E tests
```

### Linting & Formatting

```bash
npm run lint        # Oxlint + ESLint (both with --fix)
npm run format      # Prettier
```

### Setup (first time)

```bash
composer setup      # Install deps, generate key, migrate, build
```

## Architecture

### Backend (Laravel)

Standard Laravel MVC structure. Key locations:
- `app/Models/` — Eloquent models
- `app/Http/Controllers/` — HTTP request handlers
- `app/Providers/AppServiceProvider.php` — Service bootstrapping
- `routes/web.php` — HTTP routes
- `database/migrations/` — Schema as code; `database/factories/` for test data

Tests use SQLite in-memory (`phpunit.xml` configures `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).

### Frontend (Vue)

Entry points: `resources/css/app.css` (Tailwind) and `resources/js/app.js` → `bootstrap.js` (Axios + CSRF).

Vue components live in `resources/js/` with Vue Router for client-side routing and Pinia for state management. Blade templates in `resources/views/` serve as the SPA shell.

Compiled assets output to `public/build/` (git-ignored).

### Vite Configuration

`vite.config.js` excludes `resources/views/**` from watch to avoid reloads on Blade template changes.


## Additional Resources

  - Append Claude's CLAUDE.MD working memory with: ./AGENTS.md
  - Strictly follow the rules in: .agents/rules/*
