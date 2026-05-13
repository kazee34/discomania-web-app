# Discomania Agent Guide

## Quick Start

**Tech Stack:** Laravel 12 + Vue 3 + Inertia.js + TypeScript + Tailwind CSS 4  
**Architecture:** Domain-Driven Design (DDD) with event-driven admin/user sync  
**Database:** PostgreSQL (development), SQLite (testing)

### Essential Commands

```bash
# Development
npm run dev              # Vite dev server (port 5173)
composer run dev        # Full stack: Laravel + Queue + Logs + Vite
composer run dev:ssr    # With server-side rendering

# Production Build
npm run build           # Vite bundle
npm run build:ssr       # With SSR

# Testing & Linting
composer run test       # PHPUnit (Unit + Feature)
npm run lint           # ESLint with auto-fix
npm run format         # Prettier format
```

---

## Architecture Overview

### **Code Organization: DDD Per Domain**

The backend code in `src/` follows Domain-Driven Design:

```
src/
├── admin/           # Admin domain
│   ├── user/       # Admin user management
│   │   ├── domain/          (Business logic, entities, repositories)
│   │   ├── application/     (Use cases, event listeners, DTOs)
│   │   └── infrastructure/  (Controllers, Eloquent repos, routes)
│   └── product/   # Admin product management
├── customer/        # Customer domain (same structure)
└── shared/domain/   # Cross-cutting entities (UserName, UserEmail)
```

**Key Pattern:** Each domain module has three layers:
1. **Domain** — Business rules, entities, repository interfaces
2. **Application** — Use cases, event handlers, DTOs
3. **Infrastructure** — HTTP controllers, Eloquent implementations, routes

### **Frontend Organization**

```
resources/js/
├── pages/          # Inertia page components (map to routes)
├── layouts/        # AppLayout, AuthLayout, setting layouts
├── components/     # Reusable UI components (App*, Nav*, etc.)
├── composables/    # Vue composables (use* prefix)
├── actions/        # API service calls organized by domain
├── routes/         # Type-safe route helpers per feature
├── types/          # TypeScript definitions
└── lib/            # Utilities
```

---

## Key Models & Relationships

| Model | Purpose | Key Relations |
|-------|---------|----------------|
| **UserModel** | User auth, profile | HasMany personal_access_tokens, admin |
| **AdminModel** | Admin role assignment | BelongsTo User; self-reference (creator) |
| **AdminRole** | Enum: SUPER_ADMIN, ADMIN, EDITOR | Value object |

### **Event-Driven Sync Pattern**

- `UserCreatedEvent` → Auto-creates associated `AdminModel`
- `AdminDeletedEvent` → Cascades delete to `UserModel`
- Event listeners in `application/listeners/` per domain

---

## API Routes

All API routes are module-owned in `src/[domain]/[module]/infrastructure/routes/api.php`:

```
POST   /api/admin/user/store       (Create user + auto-create admin)
GET    /api/admin/user/{id}        (Retrieve user)
DELETE /api/admin/user/{id}        (Delete admin cascade)
GET    /api/user                   (Current user)
```

**Response Format:** JSON with type-safe TypeScript definitions in `resources/js/types/`

---

## Authentication & Security

- **Session Auth:** Laravel Fortify (web guard, TOTP 2FA)
- **API Auth:** Laravel Sanctum (personal access tokens)
- **Rate Limiting:** 6 attempts/min on password change
- **Verified Middleware:** Routes require email verification
- **Protected Routes:** `/settings/*`, `/dashboard`, `/api/*`

---

## Common Development Tasks

### Adding a New Feature

1. **Define Domain** → Create directory in `src/[admin|customer]/[module]/`
2. **Domain Layer** → Define entities, value objects, repository interfaces in `domain/`
3. **Use Case** → Create `*UseCase.php` in `application/`
4. **Listeners** → Add event handlers if needed in `application/listeners/`
5. **Controller** → Create REST endpoint in `infrastructure/controllers/`
6. **Repository** → Implement Eloquent version in `infrastructure/repositories/`
7. **Routes** → Register in `infrastructure/routes/api.php`
8. **Frontend** → Add action in `resources/js/actions/`, page in `pages/`

### Creating Database Migrations

```bash
php artisan make:migration create_[table]_table
```

⚠️ **Note:** Ensure `admins` table migration exists (referenced by AdminModel but migration not found).

### Writing Tests

- **Location:** `tests/Feature/`, `tests/Unit/`
- **Database:** Automatic SQLite in-memory per test
- **Command:** `composer run test`

---

## Naming Conventions

### **PHP/Backend**
- **Controllers:** `GET_*`, `POST_*`, `DELETE_*` HTTP verb prefixes
- **Use Cases:** `*UseCase.php` with `execute()` method
- **Listeners:** `*OnEventListener.php` pattern
- **PSR-4 Namespaces:** `App\`, `Src\`, `Database\`, `Tests\`

### **TypeScript/Frontend**
- **Pages:** Match route paths (e.g., `pages/auth/Login.vue`)
- **Composables:** `use*` convention (e.g., `useAppearance()`)
- **Components:** Descriptive prefixes (e.g., `AppHeader.vue`, `NavSidebar.vue`)
- **Actions:** Grouped by domain, exported functions

### **General**
- **Folders:** Lowercase, descriptive names
- **Files:** PascalCase for Vue/classes, camelCase for utilities

---

## Code Quality

### **Linting & Formatting**

```bash
npm run lint          # ESLint (JS/TS) with auto-fix
npm run format        # Prettier format
composer run lint     # PHP Pint
```

**ESLint Config:** Typescript + Vue 3 plugins configured  
**Prettier:** Integrated with Tailwind CSS plugin

### **Testing Strategy**

- **Unit Tests:** Business logic, utilities in `tests/Unit/`
- **Feature Tests:** HTTP endpoints, full workflows in `tests/Feature/`
- **In-Memory DB:** SQLite for isolation and speed

---

## Key Technologies & Why

| Technology | Reason |
|-----------|--------|
| **Inertia.js** | Seamless Vue 3 + Laravel integration without SPA complexity |
| **Fortify** | Built-in auth scaffolding with 2FA, email verification |
| **Sanctum** | Simple token-based API authentication |
| **Tailwind CSS 4** | Utility-first styling with JIT compilation |
| **Vite** | Lightning-fast dev server, optimized production builds |
| **Vue 3 Composition API** | Composables for reusable logic (useAppearance, etc.) |
| **DDD Pattern** | Scales well, isolates business logic, easy to test |

---

## Common Pitfalls to Avoid

1. **Repository Pattern:** Always depend on interfaces, not Eloquent directly (except in infrastructure layer)
2. **Events:** Use domain events for admin/user sync—don't hardcode relationships in controllers
3. **Frontend Routing:** Use `resources/js/routes/` helpers for type-safe links
4. **2FA Columns:** Columns already exist on `users` table, don't create new migrations
5. **API Responses:** All JSON responses must match `resources/js/types/` definitions
6. **Database Trigger:** Admin deletion should trigger user deletion via event listener, not database cascade

---

## File Structure at a Glance

```
discomania-app/
├── app/              (Laravel app core: Models, Controllers, Providers)
├── config/           (Laravel config: auth, cache, database, etc.)
├── database/         (Migrations, factories, seeders)
├── resources/js/     (Vue 3 + Inertia frontend)
├── routes/           (web.php, api.php, etc.)
├── src/              (DDD business logic: admin, customer, shared)
├── tests/            (PHPUnit test suites)
├── vite.config.ts    (Vite bundler config)
├── tsconfig.json     (TypeScript config)
├── package.json      (Frontend dependencies)
├── composer.json     (Backend dependencies)
└── AGENTS.md         (This file)
```

---

## Useful Links

- [Laravel Framework](https://laravel.com/docs)
- [Inertia.js Docs](https://inertiajs.com/)
- [Vue 3 Docs](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Vite Docs](https://vitejs.dev/)

---

**Last Updated:** May 2026  
**Project:** Discomania — Role-Based Admin/Customer Platform
