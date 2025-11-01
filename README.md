# Jon Russell - Portfolio Website

A modern, responsive portfolio website built with Laravel, Vue.js, and TailwindCSS, showcasing professional software engineering projects and experience.

## 🚀 Live Demo

[View Live Site](https://jonrussell.dev) <!-- Update with your actual domain -->

## 🛠️ Tech Stack

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

## ✨ Features

- **Responsive Design** - Mobile-first approach
- **Modern UI/UX** - Clean, professional interface
- **Project Showcase** - Interactive project gallery
- **Modal System** - Detailed project views
- **Type Safety** - Full TypeScript support
- **Performance Optimized** - Fast loading times
- **SEO Ready** - Meta tags and structured data

## 🏗️ Project Structure

```
├── app/                    # Laravel backend
├── resources/
│   ├── js/                # Vue.js frontend
│   │   ├── components/     # Reusable components
│   │   ├── layout/         # Layout components
│   │   ├── pages/          # Page components
│   │   ├── stores/         # Pinia stores
│   │   └── types/          # TypeScript definitions
│   ├── css/               # Stylesheets
│   └── views/             # Blade templates
├── public/                # Static assets
└── tests/                 # Test suites
```

## 🚀 Getting Started

### Prerequisites

- PHP 8.4+
- Node.js 22+
- Composer
- NPM

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/portfolio.git
   cd portfolio
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 🧪 Testing

Run the test suite:

```bash
# PHP tests
./vendor/bin/pest

# Frontend linting
npm run lint

# Code formatting
npm run format
```

## 📦 Build for Production

```bash
# Build assets
npm run build

# Build with SSR
npm run build:ssr
```

## 🎨 Customization

### Adding New Projects

Edit `resources/js/data/projects.json` to add new projects:

```json
{
  "id": "project-name",
  "title": "Project Title",
  "byline": "Brief description",
  "description": "<p>Detailed description with HTML</p>",
  "skills": ["Laravel", "Vue.js"],
  "technologies": ["REST APIs", "MySQL"],
  "tools": ["Docker", "Git"],
  "company": "Company Name",
  "primaryImage": "/images/project.png",
  "links": [{"title": "Live Demo", "url": "https://example.com"}]
}
```

### Styling

The project uses Tailwind CSS with custom theme configuration. Colors and fonts are defined in:
- `resources/css/app.css` - Custom CSS variables
- `tailwind.config.ts` - Tailwind configuration

## 🔧 Development

### Available Scripts

```bash
npm run dev          # Start development server
npm run build        # Build for production
npm run build:ssr    # Build with SSR
npm run lint         # Run ESLint
npm run format       # Format code with Prettier
```

### Code Style

- **PHP**: Follows PSR-12 standards
- **JavaScript/TypeScript**: ESLint + Prettier
- **Vue.js**: Vue 3 Composition API
- **CSS**: Tailwind CSS utility classes

## 📱 Responsive Design

The site is fully responsive with breakpoints:
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: > 1024px

## 🚀 Deployment

### Laravel Forge / Vapor
```bash
# Production build
npm run build

# Deploy
php artisan deploy
```

### Docker
```bash
docker build -t portfolio .
docker run -p 8000:8000 portfolio
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Jon Russell** - Senior Software Engineer
- Portfolio: [jonrussell.dev](https://jonrussell.dev)
- LinkedIn: [linkedin.com/in/russell-jonathan](https://linkedin.com/in/russell-jonathan)
- GitHub: [@russellio](https://github.com/russellio)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework
- [Vue.js](https://vuejs.org) - The progressive JavaScript framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Inertia.js](https://inertiajs.com) - Modern monolith approach

---

⭐ **Star this repository if you found it helpful!**
