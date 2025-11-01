# Jon Russell - Portfolio Website

[![Laravel](https://img.shields.io/badge/Laravel-BA1B2F?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)](https://vuejs.org/)
[![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

A modern, responsive portfolio website built with Laravel, Vue.js, and TailwindCSS, showcasing professional software engineering projects and experience.

## 📑 Table of Contents

- [🚀 Live Demo](#live-demo)
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

## 🚀 Live Demo <a name="live-demo"></a>

[![Jon Russell Portfolio](https://jonrussell.dev/storage/external/ss-intro-jonrussell-dev.png)](https://jonrussell.dev)

[View Live Site](https://jonrussell.dev) | [Screenshot Source](https://jonrussell.dev/storage/external/ss-intro-jonrussell-dev.png)

## 🛠️ Tech Stack <a name="tech-stack"></a>

### Backend
- **Laravel 11** - PHP framework
- **Inertia.js** - Modern monolith approach
- **SQLite** - Database
- **PHP 8.4** - Runtime

### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **TypeScript** - Type safety
- **Tailwind CSS 4** - Utility-first CSS
- **Vite** - Build tool
- **Pinia** - State management

### Development Tools 
- **ESLint** - Code linting
- **Prettier** - Code formatting
- **PEST** - PHP testing
- **GitHub Actions** - CI/CD

## ✨ Features <a name="features"></a>

- **Responsive Design** - Mobile-first approach
- **Modern UI/UX** - Clean, professional interface
- **Project Showcase** - Interactive project gallery
- **Modal System** - Detailed project views
- **Type Safety** - Full TypeScript support
- **Performance Optimized** - Fast loading times
- **SEO Ready** - Meta tags and structured data

## 🏗️ Project Structure <a name="project-structure"></a>

```
├── app/                    # Laravel application
│   ├── Http/              # Controllers and middleware
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── public/                # Publicly accessible files
├── resources/
│   ├── js/                # Frontend assets
│   │   ├── components/    # Reusable Vue components
│   │   ├── composables/   # Vue 3 composables
│   │   ├── data/          # Static data files
│   │   ├── layout/        # Layout components
│   │   ├── sections/      # Page sections
│   │   └── types/         # TypeScript type definitions
│   ├── css/              # Global styles
│   └── views/            # Blade templates
├── routes/               # Application routes
├── storage/              # Storage directory
├── tests/               # Test suites
├── .github/workflows/    # GitHub Actions workflows
├── .env.example         # Environment variables example
└── package.json         # Node.js dependencies
```

## 🚀 Getting Started <a name="getting-started"></a>

### Prerequisites <a name="prerequisites"></a>

- PHP 8.2+ (8.3 recommended)
- Node.js 20+ (LTS recommended)
- Composer 2.5+
- SQLite (or your preferred database)

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

```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```
Visit [http://localhost:8000](http://localhost:8000) in your browser.

## 🧪 Testing <a name="testing"></a>

Run the test suite:

```bash
# PHP tests
./vendor/bin/pest

# Frontend linting
npm run lint

# Code formatting
npm run format
```
## 🎨 Customization

### Styling <a name="styling"></a>

The project uses Tailwind CSS with custom theme configuration. Colors and fonts are defined in:
- `resources/css/app.css` - Custom CSS variables
- `tailwind.config.ts` - Tailwind configuration

## 🔧 Development <a name="development"></a>

### Available Scripts <a name="available-scripts"></a>

```bash
npm run dev          # Start development server
npm run build        # Build for production
npm run build:ssr    # Build with SSR
npm run lint         # Run ESLint
npm run format       # Format code with Prettier
```

### Code Style <a name="code-style"></a>

- **PHP**: Follows PSR-12 standards
- **JavaScript/TypeScript**: ESLint + Prettier
- **Vue.js**: Vue 3 Composition API
- **CSS**: Tailwind CSS utility classes

## 📱 Responsive Design <a name="responsive-design"></a>

The site is fully responsive with breakpoints:
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: > 1024px

## 🚀 Deployment <a name="deployment"></a>

### Laravel Forge / Vapor
```bash
# Production build
npm run build

# Deploy
php artisan deploy
```

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

- [Night Sky by jo_Geek](https://codepen.io/jo_Geek) - Inspiration for "Space Mode"
- [Laravel](https://laravel.com) - The PHP framework
- [Vue.js](https://vuejs.org) - The progressive JavaScript framework
- [Inertia.js](https://inertiajs.com/) - Modern monolith approach

---

<div style="text-align: center; margin:auto">
    <p>📫 Contact: <a href="https://jonrussell.dev/contact">jonrussell.dev/contact</a> &nbsp; | &nbsp;
🌐   Visit my portfolio: <a href="https://jonrussell.dev" target="_blank">jonrussell.dev</a>
    </p>
    <p>⭐ <strong>Star this repository if you found it helpful!</strong> ⭐</p>
</div>

[🔝 Back to Top](#jon-russell---portfolio-website)
