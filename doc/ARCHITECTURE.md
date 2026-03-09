# DevShort — System Architecture & Flows

> System architecture, entity relationships, and core flows for the DevShort SaaS URL Shortener Platform.

---

## Table of Contents

- [System Actors](#system-actors)
- [1. System Architecture Diagram](#1-system-architecture-diagram)
- [2. User Flow Diagram](#2-user-flow-diagram)
- [3. Redirect Flow Diagram](#3-redirect-flow-diagram)
- [4. Database ERD](#4-database-erd)
- [5. Subscription Flow Diagram](#5-subscription-flow-diagram)
- [6. Custom Domain Verification Flow](#6-custom-domain-verification-flow)
- [Component Responsibilities](#component-responsibilities)
- [Design Principles](#design-principles)

---

## System Actors

| Actor | Description | Key Permissions |
|-------|-------------|-----------------|
| **Guest** | Unauthenticated visitor | View landing/pricing, sign up, log in |
| **User** | Registered user | Dashboard, link CRUD, analytics, domains, subscription, API keys |
| **Super Admin** | Platform administrator | Plan CRUD, user management, moderation, platform analytics, domain management |

---

## 1. System Architecture Diagram

High-level overview of how the frontend, API layer, services, and data stores interact.

```mermaid
graph TB
    subgraph Client["Client Layer"]
        Browser["Browser / SPA"]
        ThirdParty["3rd Party API Consumer"]
    end

    subgraph Frontend["Frontend (Blade + TailwindCSS + JS)"]
        Landing["Landing Page"]
        Dashboard["User Dashboard"]
        AdminPanel["Super Admin Panel"]
    end

    subgraph API["API Layer (Laravel)"]
        WebRoutes["Web Routes"]
        APIRoutes["API Routes (v1)"]
        AuthMiddleware["Auth Middleware"]
        RoleMiddleware["Role Middleware"]
        RateLimiter["Rate Limiter"]
    end

    subgraph Services["Service Layer"]
        LinkService["LinkService"]
        RedirectService["RedirectService"]
        AnalyticsService["AnalyticsService"]
        SubscriptionService["SubscriptionService"]
        DomainService["DomainService"]
        QRService["QRCodeService"]
    end

    subgraph Jobs["Queue / Jobs"]
        RecordClick["RecordClickJob"]
        VerifyDomain["VerifyDomainJob"]
        ExpireLinks["ExpireLinksCommand"]
        GeoIPLookup["GeoIPLookupJob"]
    end

    subgraph Data["Data Layer"]
        MySQL["MySQL / PostgreSQL"]
        Redis["Redis (Cache + Redirect)"]
    end

    subgraph External["External Services"]
        PaymentGW["Payment Gateway"]
        GeoIP["GeoIP Provider"]
        DNS["DNS Lookup"]
    end

    Browser --> Frontend
    ThirdParty --> APIRoutes
    Frontend --> WebRoutes
    WebRoutes --> AuthMiddleware
    APIRoutes --> AuthMiddleware
    AuthMiddleware --> RoleMiddleware
    APIRoutes --> RateLimiter
    RoleMiddleware --> Services
    RateLimiter --> Services

    LinkService --> MySQL
    LinkService --> Redis
    RedirectService --> Redis
    RedirectService --> MySQL
    RedirectService --> RecordClick
    AnalyticsService --> MySQL
    SubscriptionService --> MySQL
    SubscriptionService --> PaymentGW
    DomainService --> MySQL
    DomainService --> VerifyDomain

    RecordClick --> MySQL
    RecordClick --> GeoIPLookup
    GeoIPLookup --> GeoIP
    VerifyDomain --> DNS
    ExpireLinks --> MySQL
```

---

## 2. User Flow Diagram

The journey of a user from landing page through link management.

```mermaid
flowchart LR
    A["Visit Landing Page"] --> B{"Has Account?"}
    B -- No --> C["Sign Up"]
    B -- Yes --> D["Log In"]
    C --> E["Email Verification"]
    E --> F["Dashboard"]
    D --> F

    F --> G["Create Link"]
    F --> H["View Links"]
    F --> I["View Analytics"]
    F --> J["Manage Domains"]
    F --> K["Manage Subscription"]
    F --> L["API Keys"]

    G --> M["Enter Long URL"]
    M --> N{"Set Options?"}
    N -- "Custom Alias" --> O["Enter Alias"]
    N -- "Expiration" --> P["Set Date"]
    N -- "Password" --> Q["Set Password"]
    N -- "None" --> R["Generate Link"]
    O --> R
    P --> R
    Q --> R

    R --> S["Short URL Created"]
    S --> T["Copy / Share / QR Code"]

    H --> U["Edit Link"]
    H --> V["Delete Link"]
    H --> W["View Link Stats"]
```

---

## 3. Redirect Flow Diagram

What happens when a visitor clicks a short link.

```mermaid
flowchart TD
    A["Visitor opens short URL"] --> B["Parse domain + short code"]

    B --> C{"Check Redis Cache"}
    C -- "HIT (1–5ms)" --> E
    C -- "MISS" --> D["Query Database"]
    D --> D1{"Link found?"}
    D1 -- No --> ERR["404 — Link Not Found"]
    D1 -- Yes --> D2["Store link in Redis"]
    D2 --> E

    E{"Link expired?"}
    E -- Yes --> F["410 — Link Expired Page"]
    E -- No --> G{"URL ends with + ?"}
    G -- Yes --> H["Show Link Preview Page"]
    G -- No --> I{"Password required?"}
    I -- Yes --> J["Show Password Form"]
    J --> K{"Password correct?"}
    K -- No --> L["Show Error — Retry"]
    L --> J
    K -- Yes --> M["Dispatch RecordClickJob"]
    I -- No --> M

    M --> N["Collect Analytics Data"]
    N --> |"IP, User-Agent, Referer"| O["Queue: GeoIP + Device Parse"]
    O --> P["Store in clicks table"]
    M --> Q["301 Redirect to Original URL"]

    H --> R{"User clicks 'Continue'?"}
    R -- Yes --> I
    R -- No --> S["User leaves"]

    style ERR fill:#ef4444,color:#fff
    style F fill:#f59e0b,color:#fff
    style Q fill:#22c55e,color:#fff
    style C fill:#6366f1,color:#fff
    style D2 fill:#6366f1,color:#fff
```

---

## 4. Database ERD

Entity-Relationship Diagram for all core database tables.

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role "user | admin"
        string api_key UK "nullable"
        timestamp email_verified_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    PLANS {
        bigint id PK
        string name
        string slug UK
        text description "nullable"
        decimal price_monthly
        decimal price_yearly "nullable"
        integer max_links
        integer max_domains
        boolean api_access "default false"
        string analytics_level "basic | advanced | full"
        json features "nullable, feature toggles"
        boolean is_active "default true"
        integer sort_order "default 0"
        timestamp created_at
        timestamp updated_at
    }

    SUBSCRIPTIONS {
        bigint id PK
        bigint user_id FK
        bigint plan_id FK
        string status "active | canceled | expired | trial"
        timestamp starts_at
        timestamp ends_at "nullable"
        timestamp trial_ends_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    LINKS {
        bigint id PK
        bigint user_id FK
        bigint domain_id FK "nullable"
        string original_url
        string short_code UK
        string custom_alias "nullable"
        string title "nullable"
        string password "nullable, hashed"
        timestamp expires_at "nullable"
        boolean is_active "default true"
        integer clicks_count "default 0"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "nullable, soft delete"
    }

    CLICKS {
        bigint id PK
        bigint link_id FK
        string ip_address "nullable"
        string user_agent "nullable"
        string referer "nullable"
        string referer_domain "nullable"
        string country "nullable"
        string country_code "nullable"
        string device "nullable, mobile | desktop | tablet"
        string browser "nullable"
        string os "nullable"
        boolean is_unique "default true"
        timestamp created_at
    }

    DOMAINS {
        bigint id PK
        bigint user_id FK
        string domain UK
        string verification_token "nullable"
        string status "pending | verified | failed"
        timestamp verified_at "nullable"
        string ssl_status "pending | active | error"
        boolean is_default "default false"
        timestamp created_at
        timestamp updated_at
    }

    BLACKLISTED_DOMAINS {
        bigint id PK
        string domain UK
        string reason "nullable"
        timestamp created_at
    }

    RESERVED_ALIASES {
        bigint id PK
        string alias UK
        string reason "nullable"
        timestamp created_at
    }

    USERS ||--o{ LINKS : "creates"
    USERS ||--o{ DOMAINS : "owns"
    USERS ||--o| SUBSCRIPTIONS : "has"
    PLANS ||--o{ SUBSCRIPTIONS : "defines"
    LINKS ||--o{ CLICKS : "receives"
    DOMAINS ||--o{ LINKS : "hosts"
```

---

## 5. Subscription Flow Diagram

User journey from plan selection through payment to active subscription.

```mermaid
flowchart TD
    A["User opens Pricing / Subscription page"] --> B["View available plans"]
    B --> C["Select a plan"]
    C --> D{"Current plan?"}

    D -- "No subscription (Free)" --> E["Proceed to Checkout"]
    D -- "Upgrading" --> E
    D -- "Downgrading" --> F["Schedule downgrade at period end"]

    E --> G["Enter Payment Details"]
    G --> H["Submit to Payment Gateway"]
    H --> I{"Payment successful?"}
    I -- No --> J["Show Error — Retry"]
    J --> G
    I -- Yes --> K["Payment Gateway sends Webhook"]

    K --> L["System processes webhook"]
    L --> M["Create/Update Subscription record"]
    M --> N["Update user plan limits"]
    N --> O["Send Confirmation Email"]
    O --> P["User accesses premium features"]

    F --> Q["Subscription continues until period end"]
    Q --> R["At renewal: apply downgraded plan"]
    R --> N

    subgraph Admin["Super Admin Actions"]
        SA1["Admin manual override"]
        SA1 --> SA2["Upgrade/Downgrade user"]
        SA2 --> N
        SA1 --> SA3["Extend subscription"]
        SA3 --> M
    end

    style P fill:#22c55e,color:#fff
    style J fill:#ef4444,color:#fff
```

---

## 6. Custom Domain Verification Flow

Process for connecting and verifying a user's branded domain.

```mermaid
flowchart TD
    A["User opens Domain Settings"] --> B["Click 'Add Domain'"]
    B --> C["Enter domain name"]
    C --> D["System generates verification token"]
    D --> E["Show DNS instructions"]

    E --> F["Instructions:"]
    F --> G["Add CNAME record"]
    G --> |"e.g. go.brand.com → devshort.id"| H["User configures DNS at registrar"]

    H --> I["User clicks 'Verify Domain'"]
    I --> J["System performs DNS lookup"]
    J --> K{"CNAME points to DevShort?"}

    K -- No --> L["Status: Pending / Failed"]
    L --> M["User re-checks DNS config"]
    M --> I

    K -- Yes --> N["Mark domain as Verified"]
    N --> O["Provision SSL certificate"]
    O --> P{"SSL active?"}
    P -- No --> Q["SSL Status: Pending"]
    Q --> R["Retry SSL provisioning"]
    R --> P
    P -- Yes --> S["Domain fully active"]

    S --> T["Domain available in link creation"]
    T --> U["User creates link with branded domain"]
    U --> V["e.g. go.brand.com/sale"]

    subgraph Scheduled["Scheduled Verification (Cron)"]
        SV1["Run every hour"]
        SV1 --> SV2["Check pending domains"]
        SV2 --> J
    end

    style S fill:#22c55e,color:#fff
    style L fill:#f59e0b,color:#fff
```

---

## Component Responsibilities

### Service Layer

| Service | Responsibility |
|---------|---------------|
| **LinkService** | Create, update, delete links. Generate unique short codes. Validate aliases and URLs against blacklists. Enforce plan limits. |
| **RedirectService** | Resolve short codes (Redis cache first, then DB). Handle expiration, password checks, and preview logic. Dispatch click recording. |
| **AnalyticsService** | Aggregate click data. Generate reports by time range, device, country, referrer. Provide data for dashboard charts. |
| **SubscriptionService** | Check plan limits and feature access. Handle plan changes. Interface with payment gateway. |
| **DomainService** | Add/remove domains. Generate verification tokens. Perform DNS verification. Manage SSL status. |
| **QRCodeService** | Generate QR codes for links. Support color customization and download in PNG/SVG. |

### Queue Jobs

| Job | Purpose |
|-----|---------|
| **RecordClickJob** | Async click recording — parse user-agent, extract referer domain, dispatch GeoIP lookup. |
| **GeoIPLookupJob** | Resolve IP address to country/region using GeoIP provider. |
| **VerifyDomainJob** | Perform DNS CNAME lookup for pending domain verifications. |
| **ExpireLinksCommand** | Scheduled command — deactivate links past their expiration date. |

---

## Design Principles

| Principle | How We Apply It |
|-----------|----------------|
| **Modular Architecture** | Service classes encapsulate business logic, controllers stay thin, jobs handle async work |
| **Performance** | Redis caching for redirect resolution (< 200ms), queue-based analytics to avoid blocking redirects |
| **Scalability** | Stateless services, queue workers scale independently, database indexing on `short_code` + `domain` |
| **SaaS Best Practices** | Feature gating via plan config, soft limits with upgrade prompts, webhook-driven payment flows |
| **Clean API Design** | Versioned API (`/api/v1/`), Eloquent Resources for consistent responses, Form Request validation |
| **Security** | Hashed passwords for protected links, input sanitization, rate limiting, URL blacklisting |
