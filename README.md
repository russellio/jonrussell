# Jon Russell - Portfolio Website

[![Laravel](https://img.shields.io/badge/Laravel-BA1B2F?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)](https://vuejs.org/)
[![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

A modern, responsive portfolio website built with Laravel, Vue.js, and TailwindCSS, showcasing professional software engineering projects and experience.

## 📑 Table of Contents

- [🚀 Live Site](#live-site)
- [🛠️ Tech Stack](#tech-stack)
- [✨ Features](#features)
- [🏗️ Project Structure](#project-structure)
- [🚀 Getting Started](#getting-started)
- [🧪 Testing](#testing)
- [🎨 Customization](#customization)
  - [Styling](#styling)
- [🔧 Development](#development)
  - [Available Scripts](#available-scripts)
  - [Code Style](#code-style)
- [📱 Responsive Design](#responsive-design)
- [🚀 Deployment](#deployment)
- [📄 License](#license)
- [👨‍💻 Author](#author)
- [🙏 Acknowledgments](#acknowledgments)

## 📊 Project Status

[![Tests](https://github.com/russellio/jonrussell.dev/actions/workflows/tests.yml/badge.svg)](https://github.com/russellio/jonrussell.dev/actions/workflows/tests.yml)
[![Lint](https://github.com/russellio/jonrussell.dev/actions/workflows/lint.yml/badge.svg)](https://github.com/russellio/jonrussell.dev/actions/workflows/lint.yml)

## 🚀 Live Site <a name="live-site"></a>

[![Jon Russell Portfolio](https://jonrussell.dev/storage/external/ss-intro-jonrussell-dev.png)](https://jonrussell.dev)

[View Live Site](https://jonrussell.dev) | [Screenshot Source](https://jonrussell.dev/storage/external/ss-intro-jonrussell-dev.png)

## 🛠️ Tech Stack <a name="tech-stack"></a>

### Backend
- **Laravel 12** - PHP framework
- **Inertia.js 2.0** - Modern monolith approach with SPA routing
- **SQLite** - Database (can be configured for MySQL/PostgreSQL)
- **PHP 8.3+** - Runtime (8.4 recommended)
- **Laravel Wayfinder** - Form handling and route utilities
- **Sentry** - Error tracking and performance monitoring
- **Mailgun** - Email delivery service

### Frontend
- **Vue.js 3.5.13** - Progressive JavaScript framework with Composition API
- **TypeScript 5.2.2** - Type safety throughout
- **Tailwind CSS 4.1.1** - Utility-first CSS framework
- **Vite 7.0.4** - Next-generation build tool
- **Pinia 3.0.3** - State management
- **Inertia.js Vue 3** - Server-driven SPA framework
- **FontAwesome** - Icon library
- **vue3-simple-icons** - Simple Icons integration

### Server-Side Rendering (SSR)
- **Inertia SSR** - Server-side rendering support for improved SEO and initial load performance
- Configurable via `composer dev:ssr` command

### Development Tools 
- **ESLint 9.17.0** - Code linting with Vue and TypeScript support
- **Prettier 3.4.2** - Code formatting with Tailwind plugin
- **PEST 4.1** - Modern PHP testing framework
- **Laravel Pint** - PHP code style fixer
- **Laravel Pail** - Real-time log viewer
- **GitHub Actions** - CI/CD pipelines for testing and linting
- **Stylelint** - CSS linting

## ✨ Features <a name="features"></a>

### Core Functionality
- **Single Page Application (SPA)** - Built with Inertia.js for seamless navigation without full page reloads
- **Server-Side Rendering (SSR)** - Optional SSR support for improved SEO and faster initial page loads
- **Responsive Design** - Mobile-first approach with breakpoints optimized for all devices
- **Modern UI/UX** - Clean, professional interface with smooth animations and transitions

### Interactive Features
- **Project Showcase** - Interactive project gallery with detailed modal views
- **Project Modals** - Detailed project information with images, descriptions, technologies, and key takeaways
- **Image Modal System** - Lightbox-style image viewing for project images
- **Contact Form** - Functional contact form with Mailgun email delivery integration
- **Contact Modal** - Modal-based contact form accessible from navigation
- **Skills API** - RESTful API endpoint for fetching skills categorized by type (Software Engineering, DevOps, Quality, Leadership)
- **Dynamic Skills Display** - Database-backed skills with grouped categorization
- **Smooth Scrolling** - Section-based navigation with smooth scroll behavior
- **URL-based Section Navigation** - Direct links to specific sections (about, projects, contact)

### Visual Effects
- **Animated Background** - Dynamic starfield background with animated stars and gradients
- **Scrolling "Things I Like"** - Infinite scrolling showcase of favorite tools and technologies
- **Hover Effects** - Interactive hover states on projects and cards
- **Custom Fonts** - Space Mono and Sixtyfour fonts for unique typography
- **Custom Color Theme** - Terminal-inspired color palette with custom CSS variables

### Technical Features
- **Type Safety** - Full TypeScript support throughout the frontend
- **Type Definitions** - Comprehensive TypeScript types for all data structures
- **Vue 3 Composition API** - Modern Vue.js development with Composition API
- **Composables** - Reusable Vue composables (useModal, useScrollToSection, useEscapeKey)
- **State Management** - Pinia store for global state management
- **Performance Optimized** - Fast loading times with code splitting and lazy loading
- **Error Tracking** - Sentry integration for error monitoring and performance tracking
- **SEO Ready** - Meta tags and structured data support
- **Accessibility** - ARIA labels and semantic HTML

## 🏗️ Project Structure <a name="project-structure"></a>

```
├── app/                           # Laravel application
│   ├── Filament/Resources/        # Filament CMS resources (primary content management)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Admin CRUD API controllers (Company, Position, TechStackItem)
│   │   │   ├── Api/               # Public read-only API controllers (Project, Skill, Timeline, TechStack)
│   │   │   └── ContactController.php
│   │   └── Middleware/            # HandleInertiaRequests
│   ├── Mail/                      # ContactFormMail
│   ├── Models/                    # Eloquent models (Project, Position, Company, Skill, TechStackItem, Icon, ...)
│   ├── Services/                  # Service interfaces + implementations
│   └── Providers/                 # AppServiceProvider (DI bindings)
├── config/                        # Configuration files
├── database/
│   ├── migrations/                # Schema migrations
│   └── database.sqlite            # SQLite database (dev)
├── public/
│   ├── images/                    # Static images (projects/, logos/, profile-avatar.svg)
│   └── favicons/
├── resources/
│   ├── js/
│   │   ├── components/            # Reusable Vue components
│   │   │   └── modals/            # Modal.vue, ProjectModal.vue, ContactModal.vue, ImageModal.vue
│   │   ├── composables/           # useModal.ts, useScrollToSection.ts, useEscapeKey.ts
│   │   ├── layout/                # Header.vue, Footer.vue, Nav.vue
│   │   ├── lib/                   # utils.ts (cn helper)
│   │   ├── pages/                 # SPA.vue (sole Inertia page)
│   │   ├── sections/              # AboutSection.vue, ProjectsSection.vue
│   │   ├── stores/                # modalStore.ts (Pinia)
│   │   ├── types/                 # index.d.ts (canonical TS types), globals.d.ts
│   │   ├── app.ts                 # Inertia + Pinia + Sentry bootstrap
│   │   └── ssr.ts                 # SSR entry point
│   ├── css/
│   │   ├── app.css                # Tailwind entry + custom CSS vars + global styles
│   │   ├── background-stars.css   # Starfield animation styles
│   │   └── modal.css              # Modal global styles
│   └── views/
│       ├── app.blade.php          # Inertia HTML shell
│       └── emails/                # contact.blade.php
├── routes/
│   ├── api.php                    # API routes (public + admin prefix)
│   ├── web.php                    # Web routes (all render SPA.vue via Inertia)
│   └── console.php
├── tests/
│   ├── Feature/Api/               # HTTP-level API tests
│   └── Unit/                      # Unit + some misplaced feature tests
├── .env.example                   # Environment variable reference
├── composer.json                  # PHP dependencies + dev scripts
├── package.json                   # Node dependencies + build scripts
├── vite.config.ts
├── tailwind.config.ts
└── tsconfig.json
```

## 🚀 Getting Started <a name="getting-started"></a>

### Prerequisites <a name="prerequisites"></a>

- PHP 8.3+ (8.4 recommended for CI/testing)
- Node.js 22+ (LTS recommended, matches CI configuration)
- Composer 2.5+
- SQLite (or your preferred database - MySQL/PostgreSQL supported)
- Mailgun account (for contact form functionality)

### Environment Setup <a name="environment-setup"></a>

1. **Clone the repository**
   ```bash
   git clone https://github.com/russellio/jonrussell.dev.git
   cd jonrussell.dev
   ```

2. **Install PHP dependencies**
   ```bash
   composer install --no-interaction --prefer-dist --optimize-autoloader
   ```

3. **Install Node dependencies**
   ```bash
   npm ci
   ```

4. **Environment configuration**
   Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update the following environment variables in `.env`:
   ```env
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database.sqlite
   
   # Mailgun Configuration (for contact form)
   MAIL_MAILER=mailgun
   MAILGUN_DOMAIN=your-domain.mailgun.org
   MAILGUN_SECRET=your-mailgun-secret
   MAILGUN_ENDPOINT=api.mailgun.net
   
   # Sentry Configuration (optional, for error tracking)
   SENTRY_LARAVEL_DSN=your-sentry-dsn
   SENTRY_TRACES_SAMPLE_RATE=1.0
   
   # For production
   # APP_ENV=production
   # APP_DEBUG=false
   ```

### Database Setup <a name="database-setup"></a>

1. **SQLite Setup** (Recommended for development)
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

   Or for MySQL/PostgreSQL:
   ```bash
   # Create database and update .env with credentials
   # Then run migrations
   php artisan migrate --seed
   ```

### Development Server <a name="development-server"></a>

Start the development servers:

**Option 1: Separate terminals (recommended for debugging)**
```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

**Option 2: Combined development environment**
```bash
# Start all services (Laravel, Vite, Queue, Logs) in one command
composer dev
```

**Option 3: With Server-Side Rendering**
```bash
# Build SSR bundle and start with SSR enabled
composer dev:ssr
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

## 🧪 Testing <a name="testing"></a>

Run the test suite:

```bash
# PHP tests with PEST
./vendor/bin/pest

# Or use composer script
composer test

# Frontend linting
npm run lint

# Code formatting
npm run format

# PHP code style (Laravel Pint)
./vendor/bin/pint
```

### Test Coverage

- **Unit Tests**: `SkillControllerTest`, `Models/CompanyTest`, `Models/ProjectTest`, `Models/IconTest`, `Models/PositionTest`, `Models/SkillTest`, `Models/SkillTypeTest`, `Models/TechStackItemTest`, `Mail/ContactFormMailTest`
- **Feature Tests**: `Api/ContactControllerTest`, `Api/ProjectControllerTest`, `Api/ProjectControllerShowTest`, `Api/ProjectHighlightedSkillsTest`, `Api/TechStackControllerTest`, `Api/TimelineControllerTest`, `Seeders/ProjectSeederTest`
- **CI/CD**: Automated testing on push to `main` and `develop` branches via GitHub Actions
- **Note**: No frontend test runner is configured (no Vitest/Playwright)
## 🎨 Customization

### Styling <a name="styling"></a>

The project uses Tailwind CSS 4 with custom theme configuration:

**Custom Colors:**
- Terminal-inspired palette with custom shades
- Primary (blue), Secondary (bulldog red), Gold, Bright Green
- Terminal black variants for UI elements

**Custom Fonts:**
- **Space Mono** - Monospace font for headings and code
- **Sixtyfour** - Display font for section titles

**Configuration Files:**
- `resources/css/app.css` - Custom CSS variables and global styles
- `tailwind.config.ts` - Tailwind configuration with custom theme
- Custom animations for infinite scrolling and starfield effects

## 🔧 Development <a name="development"></a>

### Available Scripts <a name="available-scripts"></a>

**NPM Scripts:**
```bash
npm run dev              # Start Vite development server
npm run build            # Build for production
npm run build:ssr        # Build with SSR support
npm run lint             # Run ESLint and auto-fix issues
npm run format           # Format code with Prettier
npm run format:check     # Check code formatting without fixing
```

**Composer Scripts:**
```bash
composer dev              # Start full dev environment (server, queue, logs, vite)
composer dev:ssr         # Start dev environment with SSR enabled
composer test            # Run PHP test suite
```

**Artisan Commands:**
```bash
php artisan serve        # Start Laravel development server
php artisan migrate      # Run database migrations
php artisan migrate --seed  # Run migrations with seeders
php artisan inertia:start-ssr  # Start Inertia SSR server
php artisan pail         # Start real-time log viewer
```

### Code Style <a name="code-style"></a>

- **PHP**: Follows PSR-12 standards, enforced with Laravel Pint
- **JavaScript/TypeScript**: ESLint 9 with Vue and TypeScript plugins + Prettier 3
- **Vue.js**: Vue 3 Composition API with `<script setup>` syntax
- **CSS**: Tailwind CSS utility classes with custom CSS variables
- **Formatting**: Automatic formatting on commit (Prettier for JS/TS, Pint for PHP)

## 📱 Responsive Design <a name="responsive-design"></a>

The site is fully responsive with breakpoints:
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: > 1024px

## 🚀 Deployment <a name="deployment"></a>

### Production Build
```bash
# Build frontend assets
npm run build

# For SSR-enabled deployment
npm run build:ssr

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Laravel Forge / Vapor
```bash
# Standard deployment
npm run build
php artisan deploy

# With SSR support
npm run build:ssr
php artisan inertia:start-ssr
```

### Environment Variables
Ensure these are set in production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `MAILGUN_DOMAIN` and `MAILGUN_SECRET` (for contact form)
- `SENTRY_LARAVEL_DSN` (optional, for error tracking)
- Database credentials

### Docker
```bash
docker build -t jonrussell.dev .
docker run -p 8000:8000 jonrussell.dev
```

## 📄 License <a name="license"></a>

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author <a name="author"></a>

**Jon Russell** - Senior Software Engineer
- Portfolio: [jonrussell.dev](https://jonrussell.dev)
- LinkedIn: [linkedin.com/in/russell-jonathan](https://linkedin.com/in/russell-jonathan)
- GitHub: [@russellio](https://github.com/russellio)

## 🙏 Acknowledgments <a name="acknowledgments"></a>

- [Night Sky by jo_Geek](https://codepen.io/jo_Geek) - Inspiration for animated starfield background
- [Laravel](https://laravel.com) - The PHP framework for building robust applications
- [Vue.js](https://vuejs.org) - The progressive JavaScript framework
- [Inertia.js](https://inertiajs.com/) - Modern monolith approach for SPAs
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Sentry](https://sentry.io) - Error tracking and performance monitoring
- [FontAwesome](https://fontawesome.com) - Icon library
- [Simple Icons](https://simpleicons.org) - Brand icons via vue3-simple-icons

---

<div style="text-align: center; margin:auto">
    <p>📫 Contact: <a href="https://jonrussell.dev/contact">jonrussell.dev/contact</a> &nbsp; | &nbsp;
🌐   Visit my portfolio: <a href="https://jonrussell.dev" target="_blank">jonrussell.dev</a>
    </p>
    <p>⭐ <strong>Star this repository if you found it helpful!</strong> ⭐</p>
</div>

---

## 📦 Notable Packages & Versions

### Backend Dependencies
- **laravel/framework**: ^12.0
- **inertiajs/inertia-laravel**: ^2.0
- **laravel/wayfinder**: ^0.1.11
- **sentry/sentry-laravel**: ^4.18
- **symfony/mailgun-mailer**: ^7.3
- **symfony/http-client**: ^7.3

### Frontend Dependencies
- **vue**: ^3.5.13
- **@inertiajs/vue3**: ^2.0.0
- **typescript**: ^5.2.2
- **tailwindcss**: ^4.1.1
- **@tailwindcss/vite**: ^4.1.11
- **vite**: ^7.0.4
- **pinia**: ^3.0.3
- **@sentry/vue**: ^10.22.0
- **@fortawesome/vue-fontawesome**: ^3.1.2
- **vue3-simple-icons**: ^15.6.0

### Development Dependencies
- **pestphp/pest**: ^4.1
- **laravel/pint**: ^1.18
- **laravel/pail**: ^1.2.2
- **eslint**: ^9.17.0
- **prettier**: ^3.4.2
- **vue-tsc**: ^2.2.4

[🔝 Back to Top](#jon-russell---portfolio-website)
