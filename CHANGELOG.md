# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-11-10

### 🎨 Design Updates & Enhancements

This release focuses on improving user experience, code organization, and adding new features to the portfolio.

### ✨ Added

#### Modal Enhancements
- **Click-outside-to-close functionality** - Users can now close modals by clicking outside the modal content
- **Customizable cancel button text** - Modal component now supports customizable cancel button text via `cancelText` prop
- **Improved ImageModal UX** - Image modal now scrolls to top when opened for better viewing experience
- **Enhanced close button styling** - Close buttons now have improved visual styling with backdrop blur and better contrast

#### Project Data Structure
- **Company logos support** - Projects now display company logos with configurable display options
- **Enhanced project data structure** - Company and primaryImage now use object-based structure for better flexibility
- **Highlighted skills field** - New `highlightedSkills` field in project data to distinguish key technologies
- **Company logo assets** - Added logos for Arca Sesami, Glen Raven, NCR Digital Insight, and Pioneering Evolution

#### Tech Stack Updates
- **Python added to tech stack** - Added Python to the About section tech stack with current focus indicator
- **Python icon support** - Added PythonIcon from vue3-simple-icons library

#### Form Improvements
- **Autocomplete attributes** - Contact form now includes proper autocomplete attributes for better browser support and accessibility

### 🔄 Changed

#### Code Organization
- **Reorganized component structure** - Modal component moved to `modals/` directory for better organization
- **Improved import organization** - All imports are now consistently organized and formatted
- **Tailwind class ordering** - All Tailwind classes are now consistently ordered for better readability
- **Code formatting improvements** - Consistent formatting applied across all components

#### Modal Components
- **Improved modal styling** - Updated modal content background color for better visual hierarchy
- **Better close button implementation** - Close buttons now use proper FontAwesome icons instead of string references
- **Enhanced ImageModal scroll behavior** - Changed from `window.scrollTo` to ref-based `scrollIntoView` for better reliability

#### CSS Improvements
- **Removed unused styles** - Cleaned up unused CSS classes including `.secondary-nav`, `.text-outline-dark`, and commented code
- **Improved button styling** - Enhanced `.btn-content` styling with better hover states and shadows
- **Modal content styling** - Updated modal content background color from terminal-black to a lighter shade

#### Type Safety
- **Type annotations** - Added proper TypeScript type annotation to `escapeToClose` function in useModal composable

### 🐛 Fixed

#### Accessibility
- **Removed invalid HTML attribute** - Fixed invalid `type="button"` attribute on anchor tag in Header component
- **Added role attribute** - Email link now has proper `role="button"` for better accessibility

### 🔧 Refactored

#### Component Improvements
- **ToggleSwitch component** - Improved code formatting, reorganized imports, and cleaned up empty style tags
- **BackgroundStars component** - Removed commented code and unnecessary comments for better maintainability
- **AboutSection component** - Removed commented code, improved styling, and better positioning of current focus indicator
- **ProjectModal component** - Improved code formatting, better attribute organization, and cleaner template structure

#### Code Quality
- **Removed commented code** - Cleaned up all commented code across components
- **Improved code consistency** - Consistent formatting and organization throughout the codebase
- **Better component structure** - Improved component organization and file structure

### 📦 Dependencies

- Updated package-lock.json with latest dependency versions

### 🎯 Technical Details

#### File Structure Changes
- `resources/js/components/Modal.vue` → `resources/js/components/modals/Modal.vue` (moved for better organization)

#### New Assets
- `public/images/logos/arca-sesami-dark.png`
- `public/images/logos/glen-raven.svg`
- `public/images/logos/ncr-digital-insight.png`
- `public/images/logos/pioneering-evolution.png`
- `public/images/logos/sesami-arca.jpg`

### 📝 Notes

This release primarily focuses on code quality improvements, user experience enhancements, and better project data structure. All changes maintain backward compatibility while improving the overall codebase maintainability.

---

## [1.0.0] - 2025-11-05

### 🎉 Initial Release

This is the first stable release of the Jon Russell Portfolio website. After extensive development and refinement, we're proud to present a fully-featured, production-ready portfolio platform.

### ✨ Features

#### Core Functionality
- **Single Page Application (SPA)** - Built with Inertia.js for seamless navigation without full page reloads
- **Server-Side Rendering (SSR)** - Optional SSR support for improved SEO and faster initial page loads
- **Responsive Design** - Mobile-first approach with breakpoints optimized for all devices
- **Modern UI/UX** - Clean, professional interface with smooth animations and transitions

#### Interactive Features
- **Project Showcase** - Interactive project gallery with detailed modal views
- **Project Modals** - Detailed project information with images, descriptions, technologies, and key takeaways
- **Image Modal System** - Lightbox-style image viewing for project images
- **Contact Form** - Functional contact form with Mailgun email delivery integration
- **Contact Modal** - Modal-based contact form accessible from navigation
- **Skills API** - RESTful API endpoint for fetching skills categorized by type (Software Engineering, DevOps, Quality, Leadership)
- **Dynamic Skills Display** - Database-backed skills with grouped categorization
- **Smooth Scrolling** - Section-based navigation with smooth scroll behavior
- **URL-based Section Navigation** - Direct links to specific sections (about, projects, contact)

#### Visual Effects
- **Animated Background** - Dynamic starfield background with animated stars and gradients
- **Scrolling "Things I Like"** - Infinite scrolling showcase of favorite tools and technologies
- **Hover Effects** - Interactive hover states on projects and cards
- **Custom Fonts** - Space Mono and Sixtyfour fonts for unique typography
- **Custom Color Theme** - Terminal-inspired color palette with custom CSS variables

#### Technical Features
- **Type Safety** - Full TypeScript support throughout the frontend
- **Type Definitions** - Comprehensive TypeScript types for all data structures
- **Vue 3 Composition API** - Modern Vue.js development with Composition API
- **Composables** - Reusable Vue composables (useModal, useScrollToSection, useEscapeKey)
- **State Management** - Pinia store for global state management
- **Performance Optimized** - Fast loading times with code splitting and lazy loading
- **Error Tracking** - Sentry integration for error monitoring and performance tracking
- **SEO Ready** - Meta tags and structured data support
- **Accessibility** - ARIA labels and semantic HTML

### 🛠️ Tech Stack

#### Backend
- Laravel 12
- Inertia.js 2.0
- PHP 8.3+
- SQLite (configurable for MySQL/PostgreSQL)
- Laravel Wayfinder
- Sentry error tracking
- Mailgun email delivery

#### Frontend
- Vue.js 3.5.13
- TypeScript 5.2.2
- Tailwind CSS 4.1.1
- Vite 7.0.4
- Pinia 3.0.3
- FontAwesome icons
- vue3-simple-icons

#### Development Tools
- ESLint 9.17.0
- Prettier 3.4.2
- PEST 4.1 (PHP testing)
- Laravel Pint (PHP code style)
- Laravel Pail (log viewer)
- GitHub Actions CI/CD

### 📦 Notable Improvements

- **Performance**: Optimized bundle size by reducing icon library imports
- **Monitoring**: Added Sentry integration for frontend and backend error tracking
- **User Experience**: Added infinite scrolling showcase of favorite tools and technologies
- **Content**: Enhanced project modals with key takeaways
- **Developer Experience**: Comprehensive development environment with `composer dev` command
- **Documentation**: Complete README with setup instructions and feature documentation

### 🧪 Testing

- Unit tests for ContactController and SkillController
- Feature tests included
- Automated CI/CD pipeline with GitHub Actions
- Code quality checks with ESLint, Prettier, and Laravel Pint

### 📝 Documentation

- Comprehensive README.md with setup instructions
- Detailed project structure documentation
- Development scripts documentation
- Deployment instructions

### 🔧 Configuration

- Environment-based configuration
- Mailgun integration for contact form
- Optional Sentry error tracking
- Database migrations and seeders
- SSR configuration support

### 🚀 Deployment Ready

- Production build scripts
- SSR build support
- Laravel optimization commands
- Environment variable documentation
- Docker support (optional)

---

## [0.9.0] - Previous Release

Initial development version with core features.

---

[1.0.1]: https://github.com/russellio/jonrussell.dev/releases/tag/v1.0.1
[1.0.0]: https://github.com/russellio/jonrussell.dev/releases/tag/v1.0.0
[0.9.0]: https://github.com/russellio/jonrussell.dev/releases/tag/v0.9.0

