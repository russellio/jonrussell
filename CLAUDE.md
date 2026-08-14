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
- `app/Http/Controllers/ContactController` — Contact form POST, sends Mailgun email
- `app/Filament/Resources/` — Filament CMS resources for all content models (sole CMS / content management interface)
- `app/Services/` — `CompanyStatsService` and `TechStackMetricsService` interfaces exist with registered empty stub impls (TODO)
- `app/Http/Middleware/HandleInertiaRequests` — Shares `name`, `quote` (random), and `auth.user` globally on every request
- `routes/api.php` — Public read-only API routes, unauthenticated except Filament's own panel auth (no admin API routes exist)

### Frontend

Entry: `resources/js/app.ts` → Inertia + Pinia setup, Sentry init.

Key frontend locations:
- `resources/js/pages/SPA.vue` — Sole Inertia page; orchestrates layout and section visibility
- `resources/js/sections/` — `AboutSection.vue`, `ProjectsSection.vue` — each fetches its own data from the API on mount
- `resources/js/components/modals/` — `Modal.vue` (base), `ProjectModal.vue`, `ContactModal.vue`, `ImageModal.vue`
- `resources/js/composables/` — `useModal.ts`, `useScrollToSection.ts`, `useEscapeKey.ts`, `useStarMode.ts` (localStorage-persisted star mode toggle)
- `resources/js/stores/modalStore.ts` — Pinia store; tracks open modal IDs as a `Set<string>`
- `resources/js/types/index.d.ts` — Canonical TypeScript types (`Project`, `Company`, `Technology`, `Tool`, etc.)
- `resources/js/lib/utils.ts` — `cn()` helper (clsx + tailwind-merge)
- `resources/css/app.css` — Tailwind entry with custom CSS vars, component classes, and global styles

### Star Background System

The "space mode" toggle is powered by `@russellio/vue-background-stars` (npm, v1.2.0+). `Header.vue` imports `BackgroundStars` and `ToggleSwitch` directly from the package. `Mountains.vue` is a local sibling overlay (no script, just fixed-position CSS shapes) rendered alongside `<BackgroundStars />` inside the `<Transition>`. Toggle state persists to `localStorage` via `useStarMode.ts`.

### Data Flow Pattern

Sections receive their data as Inertia props, not via client-side fetch. `HomeController` resolves all five section payloads (`techStack`, `skillTypes`, `positions`, `projects`, `posts`) through `app/Queries/*` query classes (`TechStackQuery`, `SkillsQuery`, `TimelineQuery`, `ProjectsQuery`, `PostsQuery`) and passes them to the `Home` Inertia page in a single request; `PostPageController` does the equivalent for a single post via `PostQuery`. Each query wraps its payload in `CachedQuery`'s response cache, invalidated on model mutation.

### Icon System

Icons render through a single Nuxt UI `<UIcon>` component, registered globally via the `@nuxt/ui/vue-plugin` Vue plugin in `app.ts` (`app.use(ui)`) — no per-component registration or lookup arrays. `icon_type` is the literal Iconify collection prefix (`lucide` or `simple-icons`) and `icon_name` is the icon's name within that collection; the two are combined at the binding site: `:name="`i-${iconType}-${iconName}`"` (see `TechStackSection.vue`). Static icon references elsewhere use a literal name directly (e.g. `name="i-lucide-award"`).

Icons are stored in the `icons` table and linked to `skills`, `tech_stack_items`, `project_technologies`, and `project_tools` via `icon_id`. The two Iconify JSON collections (`@iconify-json/lucide`, `@iconify-json/simple-icons`) are bundled offline via Nuxt UI's `icon.clientBundle` config in `vite.config.ts`; dynamic/template-literal icon names that its `scan: true` static scanner can't see must be listed explicitly in `clientBundle.icons`, or they fall back to a live Iconify CDN fetch at runtime.

## Known Issues / Open Debt

These are things the codebase has but are not yet complete or are actively wrong:

1. **Email address hardcoded** in `ContactController` — should use `env('CONTACT_EMAIL')` (key exists in `.env.example`)
2. **`CompanyStatsServiceImpl` and `TechStackMetricsServiceImpl` are empty stubs** — return `[]` with TODO comments; both are registered in the DI container
3. **Duplicate type definitions** — `ProjectModal.vue` redefines types locally instead of importing from `types/index.d.ts`

**Resolved (no longer issues):**
- **API caching** — read-only API endpoints now use response caching with invalidation on model mutations
- **Client-side fetch layer removed** — sections now receive data as Inertia props from `HomeController`/`PostPageController` (backed by `app/Queries/*`); `resources/js/lib/api.ts` no longer exists
- **Dual icon system removed** — FontAwesome and `vue3-simple-icons` were replaced with Nuxt UI's `<UIcon>` backed by `@iconify-json/lucide` and `@iconify-json/simple-icons`; `resources/js/lib/icons.ts` no longer exists
- **API Resources** — controllers standardized with model-aware formatting and type safety
- **Admin API routes/controllers removed** — there are no `admin` API routes or `app/Http/Controllers/Admin/` controllers; Filament is the sole CMS for managing content
- **Admin/Filament duplication removed** — the unused, unauthenticated Admin CRUD controllers (`CompanyController`, `PositionController`, `TechStackItemController`) were deleted; no duplication remains
- **`v-html` on database content** — `ProjectModal.vue` has exactly one `v-html`, on `project.description` (`company.name` is plain text interpolation, never `v-html`); the underlying HTML is now sanitized server-side (`mews/purifier` via `App\Http\Resources\Concerns\SanitizesHtml`) before it ever reaches `v-html`

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

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.5. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `pnpm run build`, `pnpm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `pnpm run build` or ask the user to run `pnpm run dev` or `composer run dev`.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>
