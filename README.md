# DevShort

> A modern SaaS URL shortener platform built with Laravel 12.

DevShort allows users to create short links, manage them via a dashboard, analyze link performance, and use custom branded domains — all with a subscription-based model.

---

## Features

- **URL Shortening** — Generate short links from long URLs
- **Custom Alias** — Define your own short link slug (e.g. `devshort.id/promo`)
- **Custom Branded Domain** — Connect your own domain (e.g. `go.brand.com/sale`)
- **Link Analytics** — Track clicks, devices, countries, referrers
- **QR Code Generator** — Downloadable QR codes for each link
- **Link Expiration** — Set an expiry date for links
- **Password Protected Links** — Require a password before redirecting
- **Link Preview** — Append `+` to preview before redirecting (e.g. `devshort.id/promo+`)
- **API Access** — Create and manage links via REST API
- **Subscription System** — Free, Pro, and Business plans
- **Super Admin Panel** — Platform management, user moderation, plan configuration

---

## Tech Stack

| Layer | Technology |
| --- | --- |
| **Framework** | Laravel 12 (PHP 8.2) |
| **Frontend** | Blade, TailwindCSS, Vanilla JS |
| **Database** | MySQL / PostgreSQL |
| **Cache** | Redis |
| **Testing** | Pest 3 |
| **Code Style** | Laravel Pint |
| **Queue** | Laravel Queue (database / Redis) |

---

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- MySQL 8+ or PostgreSQL 15+
- Redis (recommended)

---

## Installation

### 1. Clone the repository

```bash
git clone git@github.com:misnosugianto48/devshort.git
cd devshort
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and Redis credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devshort
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=redis
REDIS_HOST=127.0.0.1
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Build frontend assets

```bash
npm run build
```

### 6. Start the development server

```bash
# Option A: Using Laravel's built-in server
php artisan serve

# Option B: Using the dev command (runs Vite + server together)
composer run dev
```

The application will be available at `http://localhost:8000`.

---

## Development

```bash
# Run tests
php artisan test --compact

# Run code formatter
vendor/bin/pint

# Run Vite dev server (hot reload)
npm run dev

# Run queue worker
php artisan queue:work
```

---

## Documentation

- [Product Requirements (PRD)](doc/PRD.md)
- [System Architecture & Flows](doc/ARCHITECTURE.md)
- [Phased Implementation Plan](doc/PHASES.md)

---

## License

This project is proprietary software.
