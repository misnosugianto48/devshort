# DevShort — Phased Implementation Plan

> A step-by-step roadmap for building the DevShort SaaS URL Shortener Platform on Laravel 12.

---

## Phase 1 — Foundation & MVP Core

**Goal:** Establish the project foundation, authentication system, and basic URL shortening with a minimal dashboard.

### 1.1 Project Setup & Configuration ✅
- [x] Update `README.md` with DevShort branding and project description
- [x] Configure `.env` for local development (app name, DB, mail, etc.)
- [x] Set up TailwindCSS with the project's design system (colors, fonts, spacing)

### 1.2 Authentication System ✅
- [x] Implement user registration (name, email, password)
- [x] Implement user login / logout
- [x] Implement email verification
- [x] Implement password reset flow
- [x] Create auth Blade views (register, login, forgot password, reset password, verify email)
- [x] Feature tests: 22 tests, 34 assertions — all passing

### 1.3 Database Schema — Core Models
- [x] **Link** model & migration — `id`, `user_id`, `original_url`, `short_code`, `title`, `clicks_count`, `is_active`, `created_at`, `updated_at`
- [x] **Click** model & migration — `id`, `link_id`, `ip_address`, `user_agent`, `referer`, `country`, `device`, `browser`, `os`, `created_at`
- [x] Create model factories & seeders for both

### 1.4 URL Shortening — Core Logic
- [x] Service class: `LinkService` — generate unique short codes, create links, resolve links
- [x] Short code generation strategy (random alphanumeric, 6 chars default)
- [x] Redirect controller — resolve `/{shortCode}` and redirect to original URL
- [x] Record click data on each redirect (async via queued job)
- [x] Validate destination URLs (format, blacklist check)

### 1.5 User Dashboard — MVP
- [ ] Dashboard layout (sidebar navigation, topbar, content area)
- [ ] **Overview page** — total links, total clicks, recent links
- [ ] **Create Link page** — form to shorten a URL
- [ ] **Links list page** — paginated table of user's links with copy-to-clipboard
- [ ] **Link detail page** — basic click stats (total clicks, click timeline chart)

### 1.6 Landing Page
- [ ] Hero section with CTA
- [ ] Features section
- [ ] How it works section
- [ ] Pricing plans section (static for now)
- [ ] Footer

---

## Phase 2 — Link Features & Enhancements

**Goal:** Add premium link features — custom alias, expiration, password protection, link preview, and QR codes.

### 2.1 Custom Alias
- [ ] Allow users to specify a custom alias when creating a link
- [ ] Validate alias uniqueness and allowed characters (alphanumeric, hyphens)
- [ ] Reserve system-level aliases (e.g., `api`, `admin`, `dashboard`, `login`)

### 2.2 Link Expiration
- [ ] Add `expires_at` column to `links` table
- [ ] Expiration date picker in the create/edit link form
- [ ] Check expiration on redirect — show "Link Expired" page if past due
- [ ] Scheduled command to mark expired links as inactive

### 2.3 Password Protected Links
- [ ] Add `password` (hashed) column to `links` table
- [ ] Password input option in create/edit link form
- [ ] Intermediate password-entry page before redirect
- [ ] Validate password and redirect on success

### 2.4 Link Preview (`+` suffix)
- [ ] Route: `/{shortCode}+` → show link preview page
- [ ] Preview page displays: destination URL, title, click count, creation date
- [ ] Option to proceed to destination or go back

### 2.5 QR Code Generator
- [ ] Install QR code library (e.g., `simplesoftwareio/simple-qrcode`)
- [ ] Generate QR code for each link on the detail page
- [ ] Download QR code as PNG/SVG
- [ ] QR code color customization (foreground/background)

### 2.6 Link Management Enhancements
- [ ] Edit link (update alias, title, expiration, password)
- [ ] Delete link (soft delete)
- [ ] Toggle link active/inactive
- [ ] Bulk actions (delete, activate, deactivate)
- [ ] Search and filter links (by status, date range, keyword)

---

## Phase 3 — Analytics & Dashboard Enhancements

**Goal:** Build comprehensive analytics and an enhanced dashboard experience.

### 3.1 Analytics — Data Collection
- [ ] Enrich click tracking: parse user-agent for device/browser/OS
- [ ] GeoIP lookup for country detection (e.g., MaxMind GeoLite2)
- [ ] Store referer domain extraction
- [ ] Queue-based processing for all analytics data

### 3.2 Analytics — Dashboard Views
- [ ] **Click timeline** — line chart (daily/weekly/monthly)
- [ ] **Device breakdown** — pie chart (mobile vs desktop vs tablet)
- [ ] **Browser & OS breakdown** — bar charts
- [ ] **Country breakdown** — table with country flags
- [ ] **Top referrers** — ranked list
- [ ] **Unique vs total clicks** — comparison metric
- [ ] Date range filter for all analytics

### 3.3 Dashboard Enhancements
- [ ] Dashboard overview cards with sparkline charts
- [ ] Recent activity feed
- [ ] Quick-create link widget on dashboard
- [ ] Export analytics data as CSV

---

## Phase 4 — Subscription System

**Goal:** Implement the SaaS subscription model with plan management and feature gating.

### 4.1 Subscription Schema
- [ ] **Plan** model & migration — `id`, `name`, `slug`, `description`, `price_monthly`, `price_yearly`, `max_links`, `max_domains`, `features` (JSON), `is_active`, `sort_order`
- [ ] **Subscription** model & migration — `id`, `user_id`, `plan_id`, `status`, `starts_at`, `ends_at`, `trial_ends_at`
- [ ] Seed default plans (Free, Pro, Business)
- [ ] Create model factories

### 4.2 Feature Gating
- [ ] Middleware to check subscription limits (link count, domain count)
- [ ] Service class: `SubscriptionService` — check feature access, enforce quotas
- [ ] Gate premium features per plan: custom alias, custom domain, password protection, expiration, API access, advanced analytics
- [ ] Display upgrade prompts when limits are reached

### 4.3 Plan Selection & Checkout (UI)
- [ ] Pricing page with plan comparison table
- [ ] Plan selection flow in dashboard
- [ ] Subscription management page (current plan, usage, billing)
- [ ] Upgrade/downgrade flow

### 4.4 Payment Integration
- [ ] Integrate payment gateway (Stripe / Midtrans / Xendit — TBD)
- [ ] Webhook handling for payment events
- [ ] Invoice generation and history
- [ ] Trial period support

---

## Phase 5 — Super Admin Panel

**Goal:** Build the administration backend for platform management.

### 5.1 Admin Authentication & Layout
- [ ] Admin middleware (role check)
- [ ] Admin dashboard layout (separate from user dashboard)
- [ ] Admin route group under `/admin`

### 5.2 Platform Analytics
- [ ] Total users, total links, total clicks — summary cards
- [ ] New users over time chart
- [ ] Links created over time chart
- [ ] Revenue metrics (MRR, ARR) — if payment is integrated

### 5.3 User Management
- [ ] List all users with search/filter/pagination
- [ ] View user details (links, subscription, analytics)
- [ ] Suspend / unsuspend user
- [ ] Manually upgrade/downgrade user plan
- [ ] Impersonate user (login as user for support)

### 5.4 Subscription Plan Management
- [ ] CRUD interface for plans
- [ ] Configure feature limits per plan
- [ ] Set pricing (monthly/yearly)
- [ ] Toggle plan active/inactive
- [ ] Trial period configuration

### 5.5 Link Moderation
- [ ] List all links with search/filter
- [ ] Remove/disable spam or malicious links
- [ ] URL blacklist management (blocked destination domains)
- [ ] Alias blacklist management (reserved/blocked words)

### 5.6 Domain Management
- [ ] Manage default short domains
- [ ] View/verify user custom domains
- [ ] SSL and CNAME status monitoring

---

## Phase 6 — Custom Branded Domains

**Goal:** Allow users to connect their own domains for branded short links.

### 6.1 Domain Schema
- [ ] **Domain** model & migration — `id`, `user_id`, `domain`, `is_verified`, `verified_at`, `is_default`, `ssl_status`
- [ ] Create factory & seeder

### 6.2 Domain Connection Flow
- [ ] Domain setup page in dashboard
- [ ] CNAME verification instructions for the user
- [ ] Automated CNAME/DNS verification (scheduled command)
- [ ] SSL certificate provisioning (via Cloudflare or Let's Encrypt)

### 6.3 Domain-Based Routing
- [ ] Multi-domain request handling middleware
- [ ] Resolve short codes scoped to the requesting domain
- [ ] Fallback to default domain behavior

### 6.4 Domain Management in Dashboard
- [ ] List connected domains with verification status
- [ ] Re-verify / remove domain
- [ ] Set primary domain

---

## Phase 7 — Public API

**Goal:** Provide developers with RESTful API access to create and manage short links.

### 7.1 API Authentication
- [ ] Sanctum token-based authentication
- [ ] API key generation in dashboard
- [ ] API key revocation

### 7.2 API Endpoints
- [ ] `POST /api/v1/links` — Create short link
- [ ] `GET /api/v1/links` — List user's links (paginated)
- [ ] `GET /api/v1/links/{id}` — Get link details
- [ ] `PATCH /api/v1/links/{id}` — Update link
- [ ] `DELETE /api/v1/links/{id}` — Delete link
- [ ] `GET /api/v1/links/{id}/stats` — Get link analytics

### 7.3 API Features
- [ ] Eloquent API Resources for response formatting
- [ ] Rate limiting (configurable per plan)
- [ ] API documentation page (interactive, Swagger-style or custom)
- [ ] Request validation via Form Requests
- [ ] Error handling with consistent JSON responses

### 7.4 API Dashboard
- [ ] API keys management page
- [ ] API usage stats (requests made, rate limit status)
- [ ] Code examples (cURL, JavaScript, PHP, Python)

---

## Phase 8 — Polish, Performance & Launch Prep

**Goal:** Final round of polish, performance optimization, testing, and launch preparation.

### 8.1 UI/UX Polish
- [ ] Responsive design audit (mobile, tablet, desktop)
- [ ] Light animations and micro-interactions
- [ ] Loading states and skeleton screens
- [ ] Toast notifications for user actions
- [ ] Dark mode support (optional)

### 8.2 Performance
- [ ] Redis caching for link resolution (fast redirects < 200ms)
- [ ] Database query optimization and indexing
- [ ] Eager loading audit (prevent N+1 queries)
- [ ] Asset optimization (minification, compression)

### 8.3 Security
- [ ] Input sanitization and XSS prevention
- [ ] CSRF protection on all forms
- [ ] Rate limiting on public endpoints
- [ ] URL validation against malicious destinations
- [ ] DDoS mitigation strategy (Cloudflare recommendation)

### 8.4 Testing
- [ ] Feature tests for all core flows (auth, link CRUD, redirects, analytics)
- [ ] Feature tests for subscription & feature gating
- [ ] Feature tests for API endpoints
- [ ] Feature tests for admin panel
- [ ] Browser tests for critical user journeys (optional)

### 8.5 Deployment & DevOps
- [ ] Production environment configuration
- [ ] CI/CD pipeline setup
- [ ] Database backup strategy
- [ ] Monitoring and error tracking (Sentry / Laravel Telescope)
- [ ] Documentation: README, API docs, deployment guide

---

## Phase Summary

| Phase | Focus | Key Deliverables |
|-------|-------|-----------------|
| **1** | Foundation & MVP | Auth, URL shortening, basic dashboard, landing page |
| **2** | Link Features | Custom alias, expiration, password, preview, QR codes |
| **3** | Analytics | Click analytics, charts, data export |
| **4** | Subscriptions | Plans, feature gating, payment integration |
| **5** | Super Admin | Admin panel, user/plan/link management |
| **6** | Custom Domains | Branded domains, CNAME verification, multi-domain routing |
| **7** | Public API | REST API, Sanctum auth, rate limiting, docs |
| **8** | Polish & Launch | Performance, security, testing, deployment |

---

> **Next Step:** Start with **Phase 1** — we'll build the foundation together, one task at a time.
