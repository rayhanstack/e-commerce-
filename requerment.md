# MASTER PROMPT: Single-Vendor E-commerce + POS Platform (Laravel + Blade + Bootstrap 5.3)

You are a senior Laravel architect and full-stack engineer. Build the complete project described below from scratch, in phases, production-quality. Read this whole document before writing any code.

---

## 0. HOW YOU MUST WORK

1. **Plan first.** Before coding, produce an Implementation Plan and a Task List covering every phase in Section 14. Wait for my approval of the plan, then execute.
2. **Work phase by phase.** Finish one phase fully (code, migrations, seeders, tests, UI verified) before starting the next. After each phase, give a short walkthrough of what was built and how to run it.
3. **Design the database yourself.** I am not providing a schema. Design a normalized, indexed, foreign-keyed schema with migrations, factories, and seeders. Document it in `docs/database.md` (ERD in Mermaid).
4. **Verify UI in the browser.** After building any screen, open it in the browser and check it at every viewport in Section 6. Fix layout problems before moving on.
5. **Never leave placeholders** like "TODO: implement later" in delivered code. If something is intentionally deferred, list it in `docs/deferred.md` with the reason.
6. **Ask me only when blocked.** For small decisions, choose the best practice, note the choice in `docs/decisions.md`, and continue.

---

## 1. PROJECT BRIEF

Build a **single-vendor e-commerce website with a full built-in POS system**. It must be **business-agnostic**: the same codebase must work for any type of business (electronics, grocery, fashion, pharmacy, restaurant, general retail, services) through configuration, not code changes.

**First launch target: an electronics / electrical products shop.** Ship the electronics-specific module (Section 10) in the first release, but build the core so other business types can be enabled later via presets.

The project has **two panels**:

| Panel                     | URL      | Users                 | Contains                         |
| ------------------------- | -------- | --------------------- | -------------------------------- |
| **Frontend (Storefront)** | `/`      | Customers             | Shopping site, customer account  |
| **Admin**                 | `/admin` | Owner, staff, cashier | Back office + POS (`/admin/pos`) |

Core requirements:

- Single vendor now, but every business table carries `vendor_id` (via a reusable trait and global scope) so multi-vendor is a future upgrade, not a rewrite.
- POS with barcode scanning, QR codes, offline mode, receipt printing.
- **Barcode and QR code** across products, labels, orders, invoices, warranty, and stock operations.
- **Multiple payment gateways** through a pluggable adapter system.
- **Customer login by email + password.** Standard registration (name, email, password) and login form, with email verification and password reset via email. After login the customer can update their profile.
- **A unique, non-template visual design** (Section 5).
- **Fully responsive** from small mobile to large desktop (Section 6).
- **Everything UI is a reusable Blade component** (Section 7).

---

## 2. ALLOWED TECH STACK

Use exactly this stack. Do not introduce React, Vue, Inertia, or Tailwind.

**Backend**

- Latest stable **Laravel**, **PHP 8.3+**, `declare(strict_types=1)` in all new PHP files
- **MySQL 8** (PostgreSQL-compatible queries where possible)
- **Redis** for cache, sessions, queues, and rate limiting
- Laravel **Queues + Horizon**, **Scheduler**, **Events/Listeners**, **Notifications**, **Policies/Gates**, **Form Requests**, **API Resources**, **Enums**, **Observers**
- **Laravel Sanctum** (POS API and future mobile app)
- **Laravel Scout + Meilisearch** (storefront and POS search; fall back to database driver in local dev)

**Frontend**

- **Blade** templates and **Blade components** (anonymous and class-based)
- **Bootstrap 5.3 (latest)**, compiled from **Sass** through **Vite**. Import Bootstrap Sass source and override variables. Do not link the Bootstrap CDN CSS.
- **Bootstrap Icons** (npm)
- **Alpine.js** for light interactions
- **Livewire (latest stable)** only where reactivity is required: POS cart, live search, checkout steps, stock/price live updates. Everything else stays plain Blade.
- **Chart.js** for dashboards
- **Flatpickr** (dates), **Tom Select** (searchable selects), **Tiptap or Quill** (rich text)
- **html5-qrcode or ZXing-js** for camera-based barcode/QR scanning
- **Dexie.js (IndexedDB) + Workbox service worker** for POS offline mode and PWA

**Packages**

- `nwidart/laravel-modules (https://laravelmodules.com)` (modular architecture)
- `spatie/laravel-permission` (roles and permissions)
- `spatie/laravel-activitylog` (audit trail)
- `spatie/laravel-medialibrary` (images and files)
- `spatie/laravel-backup` (backups)
- `spatie/laravel-translatable` or JSON lang files (Bangla and English)
- `maatwebsite/excel` (import/export)
- `barryvdh/laravel-dompdf` (PDF invoices, challans)
- `picqer/php-barcode-generator` (barcodes)
- `simplesoftwareio/simple-qrcode` (QR)
- `brick/money` or a custom Money value object (money handling)

**Quality tooling**

- **Pest** (tests), **Laravel Pint** (style, PSR-12), **Larastan** (static analysis, level 6+), **Sentry** (error tracking, optional via `.env`)

If you believe another package is needed, add it and record why in `docs/decisions.md`.

---

## 3. ARCHITECTURE AND DESIGN PATTERNS

Use a modern, clean, layered architecture. Controllers stay thin.

**Modular monolith**

- One Laravel app split into modules with `nwidart/laravel-modules`. Each module owns its routes, controllers, models, migrations, views, lang files, config, seeders, tests, and permissions.
- Modules can be **enabled/disabled** from admin settings and by **business type preset**. A disabled module must disappear from routes, menus, permissions, and UI.
- Cross-module communication happens through **Events, Contracts (interfaces), and Services**, never through direct model reach-ins across module boundaries.

**Layers inside a module**

- `Http/Controllers`: request in, response out, nothing else
- `Http/Requests`: validation and authorization (Form Requests)
- `Actions/` (single-purpose invokable classes, e.g., `PlaceOrderAction`): business operations
- `Services/`: reusable domain logic (pricing, stock, tax, invoice numbering)
- `Repositories/`: only where query complexity justifies it; otherwise use Eloquent scopes and query objects
- `DTOs/`: typed data transfer between layers
- `Enums/`: statuses, types (order status, payment status, stock movement type)
- `Events/` + `Listeners/` + `Jobs/` + `Notifications/`
- `Policies/`: authorization per model
- `Http/Resources/`: API output
- `Observers/`: model lifecycle side effects (activity log, search index)

**Patterns to apply**

- **Adapter/Strategy** for pluggable providers: `PaymentGatewayInterface`, `SmsProviderInterface`, `CourierInterface`, `ShippingRateInterface`. Add a provider by writing one class and one config entry.
- **Business Type Preset** (Strategy + config): a preset defines enabled modules, product custom fields, default tax, POS layout options, and storefront theme defaults.
- **State machine** for order, payment, return, and service-job statuses (allowed transitions defined in one place).
- **Value Objects** for Money, PhoneNumber, Address.
- **Immutable ledgers** for stock movements and financial entries (append-only, never edited).
- **Idempotency keys** for payment webhooks and offline POS sync.
- **Database transactions and row locking** (`lockForUpdate`) around stock decrement, order placement, and payment confirmation to prevent overselling and double-charging.
- **Feature flags** through a `settings` table with caching (Redis) and a `Setting::get()` helper.
- **Money** is stored as integer minor units with a Money cast (or decimal(14,2) consistently). Pick one, document it, use it everywhere.

**Use Laravel's defaults wherever they exist.** Do not re-invent what the framework provides: route model binding, resource controllers, Form Requests, Policies, Eloquent casts, API Resources, the built-in queue/scheduler/mail/notification systems, the built-in pagination (configured for Bootstrap 5), the built-in localization, and the default Blade component system.

---

## 4. PROJECT STRUCTURE (target)

```
app/                       # Shared kernel: base classes, traits, helpers, View/Components
Modules/
  Core/  Auth/  Catalog/  Inventory/  Purchase/  Storefront/  Payment/
  Order/  Pos/  Barcode/  Customer/  Marketing/  Shipping/  Accounting/
  Hr/  Report/  Notification/  Support/  Electronics/
resources/
  views/
    components/            # Reusable Blade components (Section 7)
      ui/  form/  layout/  commerce/  admin/  pos/
    layouts/               # storefront, admin, pos, auth
  sass/                    # tokens, bootstrap overrides, component styles
  js/                      # Alpine, Livewire hooks, POS offline, scanners
lang/en  lang/bn
docs/                      # database.md, decisions.md, deferred.md, api.md, deployment.md
tests/
```

Each module has the same internal skeleton. Shared UI components live in `resources/views/components` so both panels use the same design system.

---

## 5. DESIGN SYSTEM AND VISUAL IDENTITY

The design **must not look like a stock Bootstrap template**.

- Build a **design token layer** in Sass and CSS custom properties: colors, radius, shadows, spacing scale, typography scale, motion timing, z-index. Override Bootstrap's Sass variables from these tokens so every Bootstrap component inherits the brand.
- **Theme engine:** admin can change primary color, accent color, font pair, corner radius, and dark/light default from settings; values are written as CSS variables at runtime (cached). No code change needed to rebrand.
- **Default direction (editable):** a confident, modern "electric / tech" identity: deep ink neutral base, a vivid electric-blue primary, a volt-yellow accent for sale and highlight states, generous whitespace, soft layered shadows, rounded 12–16px cards, crisp micro-interactions (hover lift, skeleton loaders, smooth drawer/modal motion). Choose a distinctive display font for headings and a clean text font for body (Google Fonts, self-hosted for performance).
- **Dark mode and light mode** across both panels, respecting `prefers-color-scheme` with a manual toggle.
- **Storefront homepage is a section builder:** admin can add, reorder, show/hide, and schedule sections (hero slider, category grid, product rows, brand strip, countdown deal, banner pair, testimonial, newsletter, custom HTML). Each section is a Blade component fed by data.
- **Admin design:** clean dashboard shell, collapsible sidebar, global command/search bar (Ctrl+K), breadcrumbs, consistent page header with actions, dense but readable tables.
- **POS design:** touch-first, large tap targets (min 44px), high-contrast, keyboard-shortcut hints, minimal chrome, works in dark mode for shop counters.
- Consistent iconography (Bootstrap Icons), consistent empty states, loading skeletons, and error states everywhere.
- Accessibility: WCAG AA contrast, visible focus rings, ARIA labels, keyboard navigable, `prefers-reduced-motion` respected.

---

## 6. RESPONSIVE REQUIREMENTS

The whole project (both panels, POS included) must be fully responsive. Use **mobile-first** CSS and the Bootstrap grid. Extend the Sass `$grid-breakpoints` map with an extra large tier.

**Device tiers to design and test at**

| Tier         | Viewport width   | Bootstrap tier |
| ------------ | ---------------- | -------------- |
| Mobile S     | 320px            | xs             |
| Mobile M     | 375px            | xs             |
| Mobile L     | 425px            | xs / sm        |
| Tablet       | 768px            | md             |
| Laptop       | 1024px           | lg             |
| Laptop L     | 1440px           | xxl            |
| Desktop / 4K | 1920px and above | xxxl (custom)  |

**Rules**

- No horizontal page scroll at any width from 320px up. Only intentionally scrollable containers (e.g., a wide table inside its own wrapper) may scroll horizontally.
- **Storefront:** product grid 2 columns on mobile, 3 on tablet, 4 on laptop, 5 on large desktop. Header collapses to hamburger + search icon + cart on mobile; mega menu on laptop and above; sticky bottom action bar (Add to cart / Buy now) on mobile product pages; filters open as an **offcanvas drawer** on mobile and sit in a sidebar on laptop and above.
- **Admin:** sidebar is an **offcanvas** below `lg`, icon-collapsed at `lg`, fully expanded at `xl` and above. Data tables switch to a **stacked card view** on mobile (each row becomes a card with labeled fields and an actions menu). Forms become single-column on mobile and multi-column on larger screens. Dashboards reflow cards and charts.
- **POS:** desktop and laptop show a two-pane layout (product grid left, cart right); tablet landscape keeps two panes; tablet portrait and mobile use tabs (Products / Cart) with a floating cart summary bar. Receipts and label prints have their own print stylesheets.
- Use fluid typography (`clamp()`), relative units (`rem`), `srcset`/`sizes` for images, `loading="lazy"`, and aspect-ratio boxes to prevent layout shift.
- Touch targets at least 44×44px on touch devices.
- Every component must be built and tested across all tiers, not just the page that uses it.

---

## 7. REUSABLE COMPONENT SYSTEM (MANDATORY)

**Rule: no UI element is written twice.** Every button, input, form, card, table, modal, badge, alert, and so on is a **Blade component** used everywhere. Raw repeated Bootstrap markup inside page views is not allowed.

**Use Laravel's default Blade component system:**

- **Anonymous components** in `resources/views/components/...` for presentational pieces, using `@props`, `$attributes->merge([...])`, default slots and named slots, and `@aware`.
- **Class-based components** in `app/View/Components/...` for components with logic (data tables, product card with price/stock state, status pills, barcode/QR renderers).
- Nested folders as namespaces: `<x-ui.button>`, `<x-form.input>`, `<x-commerce.product-card>`.
- Layouts as components: `<x-layout.storefront>`, `<x-layout.admin>`, `<x-layout.pos>`, `<x-layout.auth>` with named slots (`title`, `header`, `actions`, `scripts`).
- Follow Laravel's Breeze-style naming for form parts (`input-label`, `input-error`, `text-input`) but styled with our Bootstrap-based design tokens.
- Every component accepts extra HTML attributes (`class`, `id`, `wire:*`, `x-*`, `data-*`) through `$attributes`.
- Variants and sizes are **props** (e.g., `variant="primary|secondary|danger|ghost|link"`, `size="sm|md|lg"`, `loading`, `disabled`, `icon`, `block`), not copy-pasted classes.
- Add a **Component Gallery** page at `/admin/dev/components` (dev/local only) that renders every component in all variants, states, dark mode, and RTL for visual review.

**Minimum component inventory**

_Layout and structure:_ app shells (storefront, admin, pos, auth), `page-header`, `section-header`, `breadcrumb`, `container`, `grid`, `sidebar`, `sidebar-item`, `topbar`, `footer`, `bottom-nav` (mobile), `offcanvas`, `empty-state`, `divider`.

_Actions:_ `button`, `icon-button`, `link-button`, `button-group`, `dropdown`, `dropdown-item`, `fab`.

_Forms:_ `form` (CSRF, method spoofing, error summary, loading state), `form-group`, `label`, `input`, `textarea`, `select`, `tom-select`, `checkbox`, `radio`, `switch`, `phone-input`, `otp-input` (6 boxes, auto-advance, paste support, resend timer), `password-input`, `date-picker`, `date-range`, `file-upload`, `image-upload` (preview, multi, reorder), `rich-text`, `color-picker`, `quantity-stepper`, `search-box`, `input-error`, `help-text`, `money-input`.

_Data display:_ `card` (header/body/footer slots), `stat-card`, `table` (sortable, selectable, sticky header, responsive card-mode), `table-toolbar` (search, filters, bulk actions, export), `pagination` (Bootstrap 5 view), `badge`, `status-pill` (maps enum to color), `avatar`, `tabs`, `accordion`, `timeline`, `stepper`, `progress`, `rating`, `price` (sale/MRP/discount), `money`, `chart` (Chart.js wrapper), `list-group`, `key-value`.

_Feedback:_ `alert`, `toast` (global stack), `modal`, `confirm-dialog`, `spinner`, `skeleton`, `tooltip`, `popover`.

_Commerce:_ `product-card`, `product-gallery`, `variant-selector`, `spec-table`, `add-to-cart`, `mini-cart`, `cart-line`, `order-summary`, `address-card`, `payment-method-picker`, `coupon-box`, `review-card`, `filter-panel`, `sort-select`, `stock-badge`, `warranty-badge`, `emi-calculator`.

_Barcode/QR:_ `barcode` (renders SVG/PNG), `qr-code`, `scanner` (camera + USB keyboard-wedge input), `label-sheet`.

_POS:_ `pos-product-tile`, `pos-cart`, `pos-numpad`, `pos-payment-panel`, `pos-customer-picker`, `receipt` (58/80mm + A4).

**Also required:** shared Sass partials per component in `resources/sass/components/`, one JS module per interactive component, and the same component reused across storefront and admin wherever the pattern repeats.

---

## 8. AUTHENTICATION AND PROFILE

**Customer login (storefront): email + password.**

1. **Registration:** name, email, password (with confirmation), and mobile number (optional at signup, required later before checkout if not provided). Validate email format and password strength (min length, at least one number, standard `Password` rule object). Mobile number, if provided, is normalized (default country code Bangladesh `+880`, configurable) but is **not** used for login.
2. **Email verification:** on registration, send a signed verification link (Laravel's built-in email verification). Unverified accounts can browse and add to cart but are prompted to verify before checkout (configurable — allow or block unverified checkout from settings).
3. **Login:** email + password form, "remember me" option, standard Laravel Auth (Fortify or Breeze-style controllers, adapted to Bootstrap 5 components).
4. **Password reset:** "forgot password" sends a signed, time-limited reset link via email (Laravel's built-in password broker). No SMS dependency anywhere in this flow.
5. After login, the customer opens **My Profile** and can update: name, email (re-verification required on change), password, photo, date of birth, gender, mobile number, alternate phone, address book (multiple, default flag), and notification preferences.

**Auth security rules**

- Passwords hashed with bcrypt/argon2 (Laravel default), never stored or logged in plain text.
- Login attempt throttling per email **and** per IP (Laravel's built-in rate limiter), with exponential lockout on repeated failures.
- Log every login attempt (success, fail, blocked) in a login-attempt table.
- Session/device list in profile with "log out other devices."
- Guest checkout remains supported; a guest can optionally create an account (email + password) at the end of checkout to link the order.
- **Future-proofing:** keep the `SmsProviderInterface` adapter and an `otp-input` component in the codebase (unused by default) so mobile-number + OTP login, WhatsApp/SMS notifications, or 2FA-by-SMS can be enabled later purely via configuration, without re-architecting auth.

**Admin/staff login:** separate guard. Email + password, optional 2FA (TOTP), login attempt throttling, IP allow/deny list option, forced logout, password policy. Roles: Owner, Manager, Cashier, Stock Keeper, Accountant, Support. Permissions are granular per module and enforced by Policies on every route and every action.

---

## 9. MODULES (build all of these)

### 9.1 Core / Foundation

- Setup wizard: business type, company info, logo, currency, tax, timezone, language
- Settings: general, company, invoice, tax/VAT, currency, SEO defaults, social, theme, module toggles
- **Business type presets:** electronics, grocery, fashion, pharmacy, restaurant, general retail, service. A preset enables modules and seeds custom fields and defaults.
- Module and feature toggles from admin
- Multi-language (Bangla + English), language switcher, RTL-ready CSS; **no hardcoded UI strings**
- Multi-currency with exchange rates (default BDT), formatting helper
- Media manager
- Activity log and audit trail viewer
- Backup and restore UI (spatie/laravel-backup)
- Cache, queue, scheduler, and system health pages; error log viewer
- Global exception pages (404, 403, 419, 500, maintenance) in brand design

### 9.2 Auth and Users

- Everything in Section 8
- Role and permission manager UI
- Staff CRUD, activity per staff, login history

### 9.3 Catalog

- Categories (nested, unlimited depth), brands, units, tags
- Products: simple, variable, bundle/combo, digital, service
- Variants (color, size, capacity, model, etc.) with per-variant SKU, barcode, price, stock, images
- **Custom attribute/spec templates per category** (fields, types, filterable flag)
- Gallery, video URL, SEO meta, slug management
- Pricing: MRP, sale price, cost price, wholesale price, quantity-tier price, scheduled sale, tax class
- Status: draft, active, hidden, out of stock, scheduled
- Related, upsell, cross-sell
- Bulk import/export (CSV/Excel with validation report), bulk edit
- Product duplication, soft delete and restore

### 9.4 Inventory

- Multi-warehouse and multi-branch stock
- Stock in, stock out, transfer, adjustment, damage/loss entries
- **Immutable stock ledger** (every movement recorded with reference)
- Batch/lot with expiry; **serial number/IMEI tracking** (per-unit records)
- Reorder level, low-stock and out-of-stock alerts
- Stock audit/stocktake using barcode scan
- Stock reservation on order, release on cancel/expiry
- Real-time shared stock between storefront and POS (no overselling, use locking)
- Stock valuation (FIFO / weighted average, configurable)

### 9.5 Purchase and Supplier

- Supplier profiles, ledger, due tracking
- Purchase order, goods received note (GRN), purchase return
- Landed cost (freight, duty, other charges distributed into cost)
- Supplier payments, payment history
- Purchase reports

### 9.6 Storefront (Frontend panel)

- Homepage section builder (Section 5)
- Header with search autocomplete, mega menu, cart, wishlist, account
- Category and brand listing pages with filters (price, brand, category, specs, availability, rating), sorting, pagination
- Product page: gallery with zoom, variant selector, spec table, warranty and EMI info, stock, reviews and Q&A, related products, recently viewed, share, WhatsApp order button
- Cart (mini-cart + page), wishlist, compare (up to 4 products)
- **Checkout:** guest and logged-in, address selection, delivery zone and shipping charge, coupon, payment method selection, order review, success page
- Order tracking by order number + mobile or QR scan
- **My Account:** profile, orders, order detail, invoice PDF, address book, wishlist, wallet, loyalty points, warranty list, return requests, notifications, devices
- CMS pages (about, terms, privacy, FAQ, contact) and blog
- Store locator (branches with map link)
- SEO: sitemap.xml, robots.txt, schema.org (Product, Breadcrumb, Organization), Open Graph, canonical URLs
- Performance: lazy images, HTTP caching, fragment caching, critical CSS approach, Lighthouse target 90+
- PWA manifest and install prompt

### 9.7 Payment

- `PaymentGatewayInterface` with methods: `initiate`, `callback/return`, `webhook`, `verify`, `refund`, `status`
- Gateways as separate adapter classes, each configurable from admin (keys stored **encrypted**, sandbox/live switch, on/off, display order, logo, fee rules):
  - Local: **bKash, Nagad, Rocket, SSLCommerz, aamarPay, ShurjoPay**
  - International: **Stripe, PayPal**
  - Offline: **Cash on Delivery, bank transfer (with proof upload), store wallet, pay at store**
- Partial payment, due/credit sale, split payment across methods
- **EMI/installment** plans (bank card EMI calculator, own installment plan with schedule)
- Webhook signature verification, idempotent handling, transaction log, refund and partial refund
- Payment reconciliation report

### 9.8 Order Management

- Status flow (state machine): pending, confirmed, processing, packed, shipped, delivered, completed, cancelled, returned, refunded
- Manual/phone order creation by staff
- Invoice, packing slip, delivery challan (PDF and print), configurable invoice numbering
- Partial shipment, partial refund
- Order notes, full timeline, customer notifications on every status change
- Fraud checks: blacklist by phone/IP/address, COD limit rules
- **Returns/RMA:** request, approve, receive, refund/exchange/store credit

### 9.9 POS (inside Admin at `/admin/pos`)

- Product search (name, SKU, barcode), category quick filters, favorites
- **Barcode scanning:** USB keyboard-wedge scanners and device camera
- Multiple carts/tabs, **hold and resume** bills
- Customer select or quick-create; walk-in default
- Discounts (line, bill, coupon), tax, rounding
- **Split payment** (cash, card, bKash/Nagad, wallet, credit/due) with change calculation
- Serial/IMEI picker on sale of tracked items
- Returns and exchanges at the counter
- **Shift management:** open/close shift, opening balance, cash in/out, cash drawer reconciliation, cashier report
- Receipts: **58mm and 80mm thermal + A4**, reprint, email/SMS receipt, QR on receipt for verification
- **Offline mode:** works without internet using IndexedDB; sales stored with client-generated UUIDs; auto-sync when back online with idempotent server endpoint and conflict handling; visible online/offline indicator and pending-sync counter
- Keyboard shortcuts (search, hold, pay, new sale), touch-friendly UI
- Optional customer-facing display screen route
- Register/terminal management (multiple counters)

### 9.10 Barcode and QR

- Auto-generate barcodes (EAN-13, Code128) and support manually entered or supplier barcodes
- **Label printing:** choose template and size (A4 sheets like 24/40 per page, and roll printers), include name, price, SKU, barcode/QR; batch print from product list or purchase receipt
- QR use cases: product page link, order tracking, invoice verification, **warranty verification**, payment QR display, table QR (restaurant preset), staff attendance
- Scan-driven workflows: stock in, stock out, transfer, stocktake, POS sale, warranty lookup

### 9.11 Customer and CRM

- Customer groups, tags, segments
- **Due ledger** (customer credit/baki), collection entries, statements
- Purchase history, lifetime value, last purchase
- Loyalty points (earn and redeem rules), store wallet, referral code, membership tiers
- Birthday and festival messaging
- Blacklist and notes

### 9.12 Marketing

- Coupons (percent, fixed, free shipping, per-user limits, category/product rules)
- Flash sales, bundle offers, buy-X-get-Y
- Campaign scheduler (banners and prices switch automatically)
- Abandoned cart recovery (SMS/email sequence)
- Facebook Pixel + Conversions API, GA4, TikTok Pixel (admin-configurable IDs)
- Facebook/Instagram and Google Merchant product feeds
- Email and SMS bulk campaigns with segments and opt-out
- Price-drop and back-in-stock alerts
- Affiliate/reseller program (flag-controlled, later phase)

### 9.13 Shipping and Delivery

- Delivery zones and areas, flat/weight/order-value based charges, free shipping rules
- **Courier adapters:** Pathao, Steadfast, RedX, Paperfly (parcel creation, tracking sync, COD reconciliation)
- Own delivery staff: assign, delivery page for delivery person, proof of delivery
- Heavy-item delivery and **installation scheduling** (fridge, AC, TV)
- Store pickup option

### 9.14 Accounting

- Chart of accounts, journal entries, general ledger
- Income and expense management with categories
- Bank, cash, and mobile-wallet accounts and transfers
- Payables and receivables
- Reports: profit and loss, balance sheet, trial balance, day close, VAT/tax report
- Automatic journal postings from sales, purchases, returns, payments

### 9.15 HR (light)

- Employees, attendance (manual and QR), leave
- Salary, advances, commissions, sales targets
- Payslips

### 9.16 Reports and Dashboard

- Dashboard: today's sales (online vs POS), orders by status, top products, low stock, pending payments, cash balance, charts with date range
- Reports: sales, product, category, brand, customer, cashier, branch, payment method, courier, tax
- Stock reports: current stock, valuation, aging, dead stock, movement
- Profit per product and per order
- Export to PDF and Excel; scheduled email reports

### 9.17 Notification

- Channels: **SMS, email, web push, WhatsApp (adapter-based)**
- Template manager with variables and Bangla/English versions (order placed, shipped, delivered, email verification, password reset, due reminder, warranty expiry, low stock)
- In-app admin notification center (new order, low stock, failed payment)

### 9.18 Support

- Contact form, live chat widget (adapter to third-party or simple built-in)
- Support ticket system with status and assignment
- FAQ manager
- Review and Q&A moderation

### 9.19 Security and Operations

- CSRF, XSS filtering, secure headers (CSP), rate limiting, upload validation and scanning of file types
- Encrypted secrets for gateway keys
- Permission check on every route
- Daily automated DB backup with restore verification
- Error tracking, structured logs, health endpoint
- GDPR-style data export and account deletion request for customers

---

## 10. ELECTRONICS-SPECIFIC MODULE (first-launch business type)

Build as a separate module `Electronics`, enabled by the "electronics" preset:

- **Serial/IMEI tracking** captured at purchase receive and at sale
- **Warranty management:** period and type (brand/shop/none), starts on sale date, warranty card PDF with QR, **public warranty check by serial or QR**, expiry reminders by SMS
- **Warranty claim and RMA flow:** receive from customer, send to supplier/brand, receive back, return to customer, with status timeline and notifications
- **Service/repair job cards:** customer device intake, problem description, technician assignment, estimate and approval, parts used, status updates by SMS, final invoice
- **Spare parts and accessories** linked to main products
- **Spec templates** per category (voltage, wattage, capacity, BTU, screen size, connectivity, etc.) with spec-based filters and a **compare table**
- **EMI calculator** and EMI plans per product/bank
- **Exchange/trade-in offers**
- **Installation service booking**
- Authorized dealer badge per brand
- Pre-order and backorder for new models
- **Bulk/corporate orders:** request for quotation, quotation PDF, B2B price list, approval flow

---

## 11. CROSS-CUTTING REQUIREMENTS

- **Localization:** all strings in `lang/en` and `lang/bn`; number, date, and currency formatting per locale; Bangla digits option.
- **Performance:** eager loading (no N+1; enable `Model::preventLazyLoading()` in non-production), proper indexes, query caching for settings/menus/categories, queued heavy work (emails, SMS, exports, PDF, image conversions), image conversions to WebP with responsive sizes.
- **Security:** authorization on all actions, mass-assignment protection, validated uploads, signed URLs for private files (invoices, proofs), security headers, dependency audit.
- **SEO (storefront):** server-rendered Blade, clean slugs, meta management, structured data, sitemap.
- **API:** versioned REST API (`/api/v1`) with Sanctum for POS and future mobile app; documented in `docs/api.md`.
- **Observability:** activity log on all business models, request/queue logging, Sentry integration switch.
- **Accessibility and browser support:** last 2 versions of Chrome, Edge, Safari, Firefox; Android Chrome and iOS Safari.

---

## 12. QUALITY STANDARDS

- PSR-12 via Pint; Larastan level 6 or higher passes
- Pest feature tests for every Action/Service and every critical flow: registration, email verification, login/password reset, checkout, payment webhook, stock reservation/decrement, POS sale, offline sync, returns, permission checks
- Pest browser/E2E smoke tests for storefront checkout and POS sale (if Dusk or Pest browser plugin is available)
- Factories and seeders for realistic **demo data** (electronics catalog with 100+ products, categories, brands, variants, serials, customers, orders)
- Seeders for roles, permissions, settings, business presets
- `.env.example` fully documented; one-command local setup (`composer setup` script)
- `README.md` with install, run, test, and deploy steps; `docs/deployment.md` for production (queue workers, Horizon, scheduler cron, Redis, storage, backups)
- Commits/PR-sized changes explained in short changelog per phase

---

## 13. HARD RULES (DO / DO NOT)

**Do**

- Reuse components; if a UI pattern appears twice, extract it into a component.
- Keep controllers thin, logic in Actions/Services.
- Use Form Requests for all validation and Policies for all authorization.
- Use Enums instead of magic strings.
- Wrap multi-step writes in transactions.
- Write migrations that are reversible and indexed.
- Keep all user-facing text translatable.

**Do not**

- Do not use inline styles or repeated raw Bootstrap markup in views.
- Do not hardcode colors, fonts, currency symbols, or strings.
- Do not add React, Vue, Inertia, or Tailwind.
- Do not skip authorization checks or store secrets in plain text.
- Do not leave dead code, unused routes, or commented-out blocks.
- Do not build the multi-vendor UI now (only keep `vendor_id` ready).

---

## 14. PHASES AND DELIVERABLES

Complete each phase fully, with tests and browser verification at all viewports, before starting the next.

1. **Foundation:** Laravel install, modular setup, tooling, design tokens, Sass/Vite pipeline, base layouts, **complete reusable component library + Component Gallery**, settings, theme engine, roles/permissions, admin shell, language switching
2. **Auth and Customer identity:** OTP login, SMS adapter, profile, address book, admin login and 2FA
3. **Catalog + Inventory + Barcode/QR:** products, variants, spec templates, stock ledger, serial/batch, label printing
4. **Storefront + Cart + Checkout:** homepage builder, listing, product page, cart, wishlist, compare, checkout, account area
5. **Payment + Orders:** gateway adapters, order state machine, invoices, returns/RMA, notifications
6. **Purchase + Supplier + Customer/CRM + Dashboard**
7. **POS:** online first, then offline mode and sync, shifts, receipts, scanners
8. **Shipping + Courier + Notification channels**
9. **Electronics module:** serial, warranty, claims, service jobs, EMI, quotations
10. **Accounting + Reports + HR**
11. **Marketing + Support + SEO + PWA**
12. **Hardening:** security review, performance pass (Lighthouse, query audit), accessibility audit, backup/restore test, load test, documentation, final demo data

At the end of each phase provide: what was built, how to run/test it, screenshots (mobile, tablet, desktop) of new screens, and any decisions or deferred items.

---

## 15. DEFINITION OF DONE (whole project)

- Every module in Section 9 and 10 works end to end and can be enabled/disabled
- Customer can register/login with email + password (with email verification and password reset), shop, pay by any enabled gateway, track the order, download the invoice, and update their profile
- Cashier can run a full shift on POS, including offline sales that sync correctly, with barcode scanning and receipt printing
- Admin can rebrand the storefront from settings without touching code
- All screens pass the responsive checks in Section 6 across all seven device tiers, in light and dark mode
- No duplicated UI markup: everything comes from the component library
- Tests, static analysis, and code style checks pass
- Docs, seeders, and setup scripts let a new developer run the whole system in under 15 minutes

Begin now with Step 1 of Section 0: produce the Implementation Plan and Task List, then wait for my approval.
