# AGENTS.md

## Stack

- Laravel 12 / PHP ^8.2 / Livewire 4 / Tailwind CSS v4 / DaisyUI / WireUI
- SQLite (dev + testing), Pest 4, DomPDF
- UUID primary keys (`HasUuids`) and soft deletes on User, TravelOrder
- User must verify email (`MustVerifyEmail`)

## Commands

```bash
composer setup          # install + env + key + migrate + npm install + build
composer dev            # artisan serve + queue:listen + vite (concurrent)
composer test           # config:clear then artisan test
php artisan test        # run all Pest tests (SQLite :memory:)
npx pest tests/Feature/ExampleTest.php   # single test file
```

No CI workflows, no pint.json (uses Laravel Pint defaults).

## Livewire SFC Pattern (Critical)

This repo uses **Livewire 4 single-file components (SFC)**. Component PHP classes live **inside Blade templates** — there are no separate files in `app/Livewire/`.

- View files use the `⚡` prefix: `⚡travel-orders.blade.php`
- PHP class is embedded in a `<?php` block at the top of the `.blade.php` file
- Routes reference them as: `Route::livewire('/path', 'pages::-admin.travel-orders')`
  - The `pages::-admin.` prefix maps to `resources/views/pages/-admin/`
  - `auth::login` maps to `resources/views/auth/`
- Component namespaces in `config/livewire.php`: `layouts`, `pages`, `auth`, `emails`
- Layout: `layouts.app` (which wraps `layouts.app.sidebar`)
- To create new SFC: `php artisan make:livewire ⚡component-name` (SFC type configured in livewire.php)

## Directory Structure

```
app/Models/          TravelOrder, User, UserProfile (UUID PKs)
app/Http/Controllers TravelOrderController, TravelOrderPrintController
resources/views/
  pages/-admin/      Dashboard, travel-orders, users, settings, help, etc.
  auth/              Login, register, logout, verify-email
  components/        Shared ⚡-prefixed Blade components
  layouts/           app.blade.php (sidebar wrapper), auth.blade.php
  pdf/               DomPDF templates (travel-order.blade.php)
config/
  davao_de_oro.php   Barangay lookup data (municipality -> barangays)
  dar_hr.php         DAR positions + officers
routes/web.php       Admin routes under /admin with auth+verified middleware
database/migrations  users, travel_orders, jobs, cache
```

## Auth & Roles

Roles on User model: `super_admin`, `admin`, `editor`, `user`
- Admin panel routes protected by `can:access-admin-panels` middleware
- `super_admin` sees soft-deleted records
- Guest routes: `/login`, `/register`

## Gotchas

- `config/livewire.php` `make_command.type` is set to `sfc` — new components are single-file by default
- `composer test` clears config cache before running tests — always use it over raw `artisan test` to avoid stale config
- `RefreshDatabase` trait is commented out in `tests/Pest.php` — tests use seeded/existing data
- Vite ignores `storage/framework/views/**` in watch mode
- Tailwind v4 uses `@import "tailwindcss"` + `@plugin "daisyui"` (not the old `@tailwind` directives)
- WireUI CSS imported from vendor: `@import "../../vendor/wireui/wireui/ts/global.css"` in `resources/css/app.css`
- `@source` directives in app.css scan vendor and views for Tailwind class discovery
- Document/print routes live under `/documents` (not `/admin`) — see `routes/web.php`
- WireUI toast notifications: `$this->notification()->success('Title', 'Message')` (use `WireUiActions` trait)
