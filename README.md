# jonrussell.dev

Personal portfolio site for Jon Russell, a senior full-stack software engineer with 10+ years of experience. Live at **[jonrussell.dev](https://jonrussell.dev)**.

[![jonrussell.dev](https://jonrussell.dev/storage/external/ss-intro-jonrussell-dev.png)](https://jonrussell.dev)

---

## Overview

The site presents a work history, skills, and project portfolio through a single scrollable layout. Navigation between `/`, `/about`, `/projects`, and `/contact` is server-driven — each route returns the same Inertia page with a `scrollTo` prop, which triggers client-side smooth scrolling. There is no Vue Router, no hash-based navigation, and no separate client-side routing layer. Deep links work natively and are SSR-friendly without any additional routing overhead.

---

## Tech Stack

| Layer | Tools |
|---|---|
| Backend | PHP 8.3, Laravel 12, Eloquent ORM |
| Frontend | Vue 3 (`<script setup>`), TypeScript, Pinia |
| Routing | Inertia.js 2 (server-driven) |
| Styling | Tailwind CSS 4, Space Mono + Sixtyfour (self-hosted via `@fontsource`) |
| Build | Vite 7, Laravel Vite Plugin |
| CMS | Filament 3 (`/admin`) |
| Email | Mailgun via Symfony Mailer |
| CAPTCHA | Cloudflare Turnstile |
| Error Tracking | Sentry (Laravel + `@sentry/vue`) |
| Testing | PEST 4, SQLite in-memory |
| SSR | Inertia.js SSR server (`ssr.ts`) |

---

## Architecture Notes

**Single Inertia page, server-driven scroll.** All four public routes render `SPA.vue` with an optional `scrollTo` prop. On mount, `SPA.vue` waits for the target section to signal readiness via a `@ready` event before initiating the scroll — with a 1.5-second timeout that falls back gracefully to the home position. This makes URLs like `/projects` deep-linkable and behaves correctly on first load, hard refresh, and back-navigation.

**Read-only JSON API with response caching.** Public data (projects, skills, tech stack, timeline) is served from controllers in `Api/` that cache responses for one hour using Laravel's cache layer. Cache is invalidated on model mutations via Eloquent events. Non-existent slugs are cached with a false-sentinel for 5 minutes to prevent database hits from repeated 404 requests.

**Filament CMS.** All content — projects, companies, positions, skills, tech stack items, and icons — is managed through a Filament 3 admin panel. The schema uses a normalized icon system: icons live in their own table and are referenced by foreign key across skills, tech stack items, and project technologies/tools.

**Single Iconify-backed icon system.** Icons render through Nuxt UI's `<UIcon>`, backed by the `@iconify-json/lucide` and `@iconify-json/simple-icons` collections. Each icon record's `icon_type` is the literal Iconify collection prefix (`lucide` or `simple-icons`), combined with `icon_name` at render time (`i-${icon_type}-${icon_name}`). Collections are bundled offline via Nuxt UI's `icon.clientBundle` config so no live Iconify CDN fetch is needed at runtime.

**Type-safe route references.** Laravel Wayfinder generates typed route helpers from backend route definitions, eliminating hardcoded URL strings on the frontend.

---

## Frontend Highlights

**Procedural star field.** `BackgroundStars.vue` generates ~1,000 DOM nodes — blinking stars in multiple size classes, nebula blur elements, and a mountain silhouette — in batched `DocumentFragment` appends triggered via `requestAnimationFrame`. No canvas. Lazy-loaded behind a "space mode" toggle with a CSS fade transition; the fallback deep-blue radial gradient renders immediately.

**CSS-animated tech stack bars.** Each stack item renders a progress bar driven entirely by a `--percent` CSS custom property set inline from the API response. Active items get a moving gradient shimmer via `@keyframes`. No JavaScript animation loop.

**Infinite scroll marquee.** `ScrollingThingsILike.vue` renders the icon list twice and drives the scroll with a single CSS keyframe animation, edge-faded using a `mask` gradient. Hover reveals a label with a fade-slide transition.

**XSS-safe HTML rendering.** Project, position, and post content from the CMS is rendered via `v-html`, but it's sanitized server-side with `mews/purifier` (HTMLPurifier) before it's ever sent to the client.

**Async section loading.** The `ScrollingThingsILike` component is loaded via `defineAsyncComponent`, keeping the initial bundle lighter.

---

## Design & UX

The visual identity uses a dark terminal-inspired palette defined as CSS custom properties in `app.css` — a deep blue (`#041028`) base, bright green (`#45f415`) for active states, bulldog red for headings, and a 10-step `terminal-black` scale for text/surface variation. Typography pairs **Sixtyfour** (display headings) with **Space Mono** (labels, monospace content), both self-hosted via `@fontsource` to avoid external font requests.

Tailwind classes are composed with `@apply` inside `<style scoped>` blocks rather than scattered inline, keeping templates readable and styles colocated with their component.

---

## Live Site & Contact

**[jonrussell.dev](https://jonrussell.dev)**  
[LinkedIn](https://www.linkedin.com/in/russell-jonathan/) · [GitHub](https://github.com/russellio)

---

## Key Technologies

`PHP 8.3` · `Laravel 12` · `Inertia.js 2` · `Vue 3` · `TypeScript` · `Tailwind CSS 4` · `Vite 7` · `Pinia` · `Filament 3` · `PEST 4` · `Mailgun` · `Cloudflare Turnstile` · `Sentry` · `Nuxt UI` · `mews/purifier` · `Laravel Wayfinder`

`FontAwesome` and `vue3-simple-icons` were used previously and have been fully removed, replaced by Nuxt UI's `<UIcon>`.
