# CLAUDE.md

This file provides guidance to Claude Code when working with code in this repository.

## Tech Stack

Full-stack Laravel 12 + Vue 3 (Inertia.js) application. **Not** a traditional SPA with Vue Router — routing is server-driven via Inertia.

- **Backend**: PHP 8.3+, Laravel 12, Eloquent ORM, SQLite (dev) / MySQL (prod)
- **Admin CMS**: Filament 3 (`/admin`) — manages all content (projects, companies, positions, skills, icons, tech stack)
- **Frontend**: Vue 3 Composition API (`<script setup>`), TypeScript, Pinia, Tailwind CSS 4, Inertia.js 2
- **Build**: Vite 7, Laravel Vite Plugin, `vue-tsc` for type-checking
- **Testing**: PEST 4 (backend only — no frontend test runner configured)
- **Linting/Formatting**: ESLint 9 (Vue + TS plugins), Prettier 3, Stylelint 16, Laravel Pint (PHP)
- **Email**: Mailgun via Symfony Mailer
- **Error Tracking**: Sentry (backend via `sentry/sentry-laravel`, frontend via `@sentry/vue`)
- **CAPTCHA**: Cloudflare Turnstile (`VITE_TURNSTILE_SITE_KEY`) on the contact form

## Commands

### Development

```bash
composer dev        # Recommended: PHP server + queue + Pail logs + Vite (all in one)
composer dev:ssr    # Same but with Inertia SSR instead of Vite HMR
npm run dev         # Vite dev server only
```

### Build

```bash
npm run build       # Production build (frontend assets)
npm run build:ssr   # Production build with SSR bundle
```

### Type-check & Lint

```bash
npx vue-tsc --noEmit   # TypeScript check
npm run lint            # ESLint --fix
npm run format          # Prettier --write resources/
npm run format:check    # Prettier check only
vendor/bin/pint         # PHP code style (run after any PHP edits)
vendor/bin/pint --dirty # Pint on changed files only
```

### Testing

```bash
composer test               # Clears config cache, then runs PEST
php artisan test            # PEST directly
php artisan test --filter=TestName  # Single test
```

### Database & Artisan

```bash
php artisan migrate --seed        # Migrate + seed
php artisan route:list --except-vendor  # Inspect routes
php artisan pail                  # Real-time log viewer
```

## Architecture

### Request Flow

```
Browser → web.php (Inertia::render('SPA')) → SPA.vue (single page shell)
       → api.php → Api/ controllers → JSON responses consumed by Vue components
```

The entire site is a single Inertia page (`SPA.vue`). Navigation between `/, /about, /projects, /contact` is handled server-side — each route renders `SPA.vue` with a `scrollTo` prop, which triggers smooth-scroll or modal open client-side.

### Backend

- `app/Models/` — Eloquent models. Key: `Project` (with 6 child has-many relationships), `Position`, `Company`, `Skill`/`SkillType`, `TechStackItem`, `Icon`
- `app/Http/Controllers/Api/` — Public read-only JSON API (`ProjectController`, `SkillController`, `TimelineController`, `TechStackController`)
- `app/Http/Controllers/Admin/` — Admin CRUD API (`CompanyController`, `PositionController`, `TechStackItemController`) — **currently unauthenticated, Sanctum installed but not applied**
- `app/Http/Controllers/ContactController` — Contact form POST, sends Mailgun email
- `app/Filament/Resources/` — Filament CMS resources for all content models (primary content management interface)
- `app/Services/` — `CompanyStatsService` and `TechStackMetricsService` interfaces exist with registered empty stub impls (TODO)
- `app/Http/Middleware/HandleInertiaRequests` — Shares `name`, `quote` (random), and `auth.user` globally on every request
- `routes/api.php` — All routes unauthenticated except Filament's own panel auth. Admin group under `Route::prefix('admin')` has no middleware guard.

### Frontend

Entry: `resources/js/app.ts` → Inertia + Pinia setup, Sentry init.

Key frontend locations:
- `resources/js/pages/SPA.vue` — Sole Inertia page; orchestrates layout and section visibility
- `resources/js/sections/` — `AboutSection.vue`, `ProjectsSection.vue` — each fetches its own data from the API on mount
- `resources/js/components/modals/` — `Modal.vue` (base), `ProjectModal.vue`, `ContactModal.vue`, `ImageModal.vue`
- `resources/js/composables/` — `useModal.ts`, `useScrollToSection.ts`, `useEscapeKey.ts`
- `resources/js/stores/modalStore.ts` — Pinia store; tracks open modal IDs as a `Set<string>`
- `resources/js/types/index.d.ts` — Canonical TypeScript types (`Project`, `Company`, `Technology`, `Tool`, etc.)
- `resources/js/lib/utils.ts` — `cn()` helper (clsx + tailwind-merge)
- `resources/css/app.css` — Tailwind entry with custom CSS vars, component classes, and global styles

### Data Flow Pattern

Vue components fetch data directly from the API on `onMounted` using raw `fetch()`. There is no centralized API client — each component manages its own loading/error state. This is a known gap.

### Icon System

Two icon sets co-exist:
- **FontAwesome** — `icon_type: 'fa'`, referenced by `icon_name` (e.g. `laravel`)
- **Simple Icons** (via `vue3-simple-icons`) — `icon_type: 'si'`, referenced by component `__name`

Icons are stored in the `icons` table and linked to `skills`, `tech_stack_items`, `project_technologies`, and `project_tools` via `icon_id`.

**Current issue**: `library.add()` is called inside individual component `<script setup>` blocks rather than once in `app.ts`.

## Known Issues / Open Debt

These are things the codebase has but are not yet complete or are actively wrong:

1. **Admin routes have no auth guard** — `Route::prefix('admin')` in `api.php` is fully public
2. **Admin controllers duplicate Filament** — both manage the same models; the API-based admin controllers appear unused
3. **Email address hardcoded** in `ContactController` — should use `env('CONTACT_EMAIL')` (key exists in `.env.example`)
4. **`CompanyStatsServiceImpl` and `TechStackMetricsServiceImpl` are empty stubs** — return `[]` with TODO comments; both are registered in the DI container
5. **Duplicate type definitions** — `ProjectModal.vue` redefines types locally instead of importing from `types/index.d.ts`
6. **`v-html` on database content** — `ProjectModal.vue` renders `description` and `company.name` via `v-html`

**Resolved (no longer issues):**
- **API caching** — read-only API endpoints now use response caching with invalidation on model mutations
- **API client** — centralized at `resources/js/lib/api.ts` with `get<T>()` function
- **FontAwesome registration** — centralized at `resources/js/lib/icons.ts`, imported in `app.ts`
- **API Resources** — controllers standardized with model-aware formatting and type safety

**Resolved (no longer issues):**
- **API client** — centralized at `resources/js/lib/api.ts` with `get<T>()` function
- **FontAwesome registration** — centralized at `resources/js/lib/icons.ts`, imported in `app.ts`
- **API Resources** — controllers standardized with model-aware formatting and type safety

## Testing

Tests use PEST 4 with SQLite in-memory (configured in `phpunit.xml`).

- `tests/Feature/Api/` — HTTP-level API tests (correct location for most new tests)
- `tests/Unit/` — Mix of true unit tests and misplaced feature-style tests (e.g. `ContactControllerTest.php`)
- No model factories exist — tests use `Model::create()` directly with raw arrays

When writing new tests, use factories (`php artisan make:factory`) and put HTTP-touching tests in `tests/Feature/`.

## Conventions

- **PHP**: PSR-12, enforced by Pint. Return types and parameter types on all methods. No `any` equivalents.
- **Vue**: `<script setup lang="ts">` — no Options API. Import canonical types from `@/js/types/index`.
- **TypeScript**: No `any`. Use `unknown` + narrowing. Types in `types/index.d.ts`; don't redefine locally.
- **Tailwind**: Use `@apply` in `<style scoped>` blocks for component-local classes. Global utilities via `app.css`.
- **CSS variables**: Custom color tokens defined in `app.css` (e.g. `--color-bright-green`, `--color-terminal-black`).
- **Git**: Branch from `develop`. PRs target `develop`, not `main`. Commit format: `feat(DEV-123): ...`

## Environment Variables (key ones)

```env
CONTACT_EMAIL=         # Recipient for contact form (currently hardcoded in ContactController — fix pending)
VITE_TURNSTILE_SITE_KEY=  # Cloudflare Turnstile sitekey for contact form CAPTCHA
VITE_APP_NAME=         # App name passed to frontend
SENTRY_LARAVEL_DSN=    # Backend Sentry DSN
# VITE_SENTRY_DSN is hardcoded in app.ts — should be moved here
```

## Additional Resources

- Append Claude's CLAUDE.md working memory with: `./AGENTS.md`
- Strictly follow the rules in: `.agents/rules/*`
- API documentation (if it exists): `./docs/API.md`
