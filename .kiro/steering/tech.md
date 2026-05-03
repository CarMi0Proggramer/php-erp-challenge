# Tech Stack

## Backend
- **PHP 8.3** / **Laravel 13**
- **Laravel Fortify** — authentication (login, password reset, 2FA)
- **Inertia.js (Laravel adapter v3)** — server-driven SPA bridge, no separate API
- **Laravel Wayfinder** — generates type-safe TypeScript route helpers from PHP controllers (auto-generated into `resources/js/routes/` and `resources/js/actions/`, do not edit manually)
- **tucker-eric/eloquentfilter** — model filtering via `ModelFilter` classes
- **PostgreSQL** (default/dev), configurable via `DB_CONNECTION`
- **Queue**: database driver; jobs dispatched for async work (e.g., image deletion)

## Frontend
- **Vue 3** (Composition API, `<script setup>`)
- **TypeScript** (strict, checked via `vue-tsc`)
- **Inertia.js Vue 3 adapter**
- **Tailwind CSS v4** (Vite plugin, no `tailwind.config.js`)
- **Shadcn Vue** — headless UI primitives (used as the base for `components/ui/`)
- **TanStack Table v8** — table logic
- **Tiptap v3** — rich text / WYSIWYG editor
- **lucide-vue-next** — icons
- **VueUse** — composable utilities
- **vue-sonner** — toast notifications

## Build & Tooling
- **Vite 8** — frontend bundler
- **ESLint 9** (flat config) + **Prettier** — linting and formatting
- **Husky** — pre-commit hooks (runs lint + format checks)
- **PHPUnit 12** — PHP tests
- **Laravel Pint** — PHP code style (PSR-12 based)

## Common Commands

```bash
# Start all dev processes (server, queue, logs, vite) concurrently
composer run dev

# Run all tests (clears config cache, checks PHP lint, runs PHPUnit)
composer run test

# PHP linting (Laravel Pint)
composer run lint          # fix
composer run lint:check    # check only

# Frontend
npm run dev                # Vite dev server
npm run build              # production build
npm run lint               # ESLint fix
npm run lint:check         # ESLint check only
npm run format             # Prettier fix
npm run format:check       # Prettier check only
npm run types:check        # vue-tsc type check

# Full CI check (lint + format + types + tests)
composer run ci:check

# Artisan shortcuts
php artisan migrate
php artisan db:seed
php artisan queue:listen --tries=1
```

## Environment
- Copy `.env.example` to `.env` and run `php artisan key:generate`
- DB is created using a docker-compose.yml file
- Seed admin credentials via `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` env vars
