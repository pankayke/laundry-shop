# FreshKlean / GeloWash Laundry Shop — Codebase Overview

---

## 1. Executive Summary

**GeloWash Laundry Shop** is a full-stack, production-ready laundry shop management system designed for a small laundry business in General Santos City, Philippines. The application handles the complete laundry workflow — from customer order placement and self-service registration, through staff processing (washing, drying, folding), to payment recording and PDF receipt generation.

The system supports **three distinct user roles** (Admin, Staff, Customer), each with their own dashboard and set of permissions. Key features include auto-generated digital laundry tickets, a live price calculator based on configurable per-kilogram rates, a 6-step real-time order status timeline, in-app and email notifications, payment recording (Cash / GCash / Maya), daily sales reporting with PDF and CSV export, and thermal-style 80 mm PDF receipts. Customers can self-register using their Philippine mobile number, submit laundry requests online (with GCash/Maya pre-payment), and track their orders publicly without logging in.

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        Browser (End User)                           │
│    Landing Page  ·  Customer Dashboard  ·  Staff Dashboard          │
│    Admin Dashboard  ·  Order Tracking  ·  Filament Admin Panel      │
└───────────────────────────────┬─────────────────────────────────────┘
                                │  HTTP (Blade + Alpine.js + Tailwind CSS)
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    Laravel 12 Application (PHP 8.2+)                │
│  Routes → Middleware (RoleMiddleware) → Controllers → Views         │
│  Services (PriceCalculator, TicketNumber) · Jobs · Notifications    │
│  Filament 3.3 Admin Panel (Resources + Widgets)                     │
└───────────────────────────────┬─────────────────────────────────────┘
                                │  Eloquent ORM
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                   Database (SQLite default / MySQL)                  │
│  users · orders · order_items · settings · notifications · jobs     │
└─────────────────────────────────────────────────────────────────────┘
```

### Tech Stack Summary

| Layer            | Technology                                              |
|------------------|---------------------------------------------------------|
| Backend          | Laravel 12 (PHP 8.2+)                                  |
| Admin Panel      | Filament 3.3 (auto-generates CRUD + dashboard widgets)  |
| Frontend/Views   | Blade Templates + Tailwind CSS v4 + Alpine.js           |
| Build Tool       | Vite 7 with Tailwind CSS plugin                         |
| Database         | SQLite (default) or MySQL 8+                            |
| PDF Generation   | barryvdh/laravel-dompdf                                 |
| Permissions      | spatie/laravel-permission                               |
| Notifications    | Laravel built-in (Database + Mail channels)             |
| SMS (Optional)   | Twilio (pre-configured, not installed by default)       |
| Queue            | Database driver (for async notifications)               |

---

## 2. Project Structure

```
laundry_shop/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── OrderResource.php            ← Filament CRUD for Orders
│   │   │   ├── OrderResource/Pages/         ← List / Create / Edit pages
│   │   │   ├── SettingResource.php           ← Filament CRUD for Shop Settings
│   │   │   ├── SettingResource/Pages/
│   │   │   ├── UserResource.php              ← Filament CRUD for Users
│   │   │   └── UserResource/Pages/
│   │   └── Widgets/
│   │       ├── OrdersChart.php               ← Bar chart: orders this week
│   │       ├── RecentOrdersTable.php         ← Dashboard: 10 most recent orders
│   │       ├── RevenueByMethodChart.php      ← Pie chart: Cash/GCash/Maya split
│   │       └── StatsOverview.php             ← Stat cards: orders, revenue, etc.
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php   ← Admin dashboard with aggregated stats
│   │   │   │   ├── SalesController.php       ← Sales report, PDF & CSV export
│   │   │   │   ├── SettingController.php     ← Edit/update shop settings
│   │   │   │   └── UserController.php        ← CRUD for managing users
│   │   │   ├── Api/
│   │   │   │   └── CustomerLookupController.php ← AJAX customer search by phone
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php       ← Dual login (email or phone)
│   │   │   │   └── RegisterController.php    ← Self-registration (customers)
│   │   │   ├── Customer/
│   │   │   │   ├── DashboardController.php   ← Customer dashboard: active & past orders
│   │   │   │   ├── OrderController.php       ← Cancel pending orders
│   │   │   │   ├── OrderRequestController.php← Submit new laundry request
│   │   │   │   └── OrderTrackingController.php ← Public order tracking page
│   │   │   ├── Staff/
│   │   │   │   ├── DashboardController.php   ← Staff dashboard: categorized order queues
│   │   │   │   └── OrderController.php       ← Create, edit, approve, payment, search
│   │   │   ├── Controller.php                ← Base controller
│   │   │   └── ReceiptController.php         ← Generate 80mm thermal PDF receipt
│   │   └── Middleware/
│   │       └── RoleMiddleware.php            ← Guards routes by user role
│   ├── Jobs/
│   │   └── SendOrderReadyNotification.php    ← Queued job: notify when order is ready
│   ├── Models/
│   │   ├── Order.php                         ← Laundry order with statuses & payment
│   │   ├── OrderItem.php                     ← Line item: cloth type, weight, service
│   │   ├── Setting.php                       ← Singleton shop configuration
│   │   └── User.php                          ← Users with role (admin/staff/customer)
│   ├── Notifications/
│   │   ├── OrderApprovedNotification.php     ← Sent when staff approves a request
│   │   ├── OrderCancelledNotification.php    ← Sent when customer cancels an order
│   │   └── OrderReadyNotification.php        ← Sent when laundry is ready for pickup
│   ├── Providers/
│   │   ├── AppServiceProvider.php            ← Force HTTPS in production
│   │   └── Filament/
│   │       └── AdminPanelProvider.php        ← Filament panel configuration
│   └── Services/
│       ├── PriceCalculatorService.php        ← Calculates subtotals & order totals
│       └── TicketNumberService.php           ← Generates sequential ticket: GW-2026-0001
├── bootstrap/                                ← Laravel bootstrap (app.php, providers.php)
├── config/                                   ← Framework configuration files
├── database/
│   ├── factories/
│   │   └── UserFactory.php                   ← Factory for generating test users
│   ├── migrations/
│   │   ├── ...create_users_table.php         ← Standard Laravel users table
│   │   ├── ...create_orders_table.php        ← Orders with status, payment fields
│   │   ├── ...create_order_items_table.php   ← Individual items per order
│   │   ├── ...create_settings_table.php      ← Shop configuration row
│   │   ├── ...create_notifications_table.php ← Laravel notification storage
│   │   └── ...create_permission_tables.php   ← Spatie permission tables
│   └── seeders/
│       ├── DatabaseSeeder.php                ← Seeds admin, staff, customers, 25 orders
│       └── SettingSeeder.php                 ← Seeds default shop settings
├── public/                                   ← Public web root (index.php, manifest.json, sw.js)
├── resources/
│   ├── css/app.css                           ← Tailwind CSS entry point
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php           ← Admin dashboard view
│       │   ├── sales.blade.php               ← Sales report view (date filter)
│       │   ├── sales-pdf.blade.php           ← PDF template for sales export
│       │   ├── settings.blade.php            ← Shop settings form
│       │   └── users/                        ← User management views (index, create, edit)
│       ├── auth/
│       │   ├── login.blade.php               ← Login page
│       │   └── register.blade.php            ← Registration page (phone-based)
│       ├── components/
│       │   ├── glass-toast.blade.php         ← Toast notification component
│       │   ├── payment-badge.blade.php       ← Payment status badge
│       │   ├── status-badge.blade.php        ← Order status badge
│       │   ├── status-timeline.blade.php     ← 6-step status timeline (full)
│       │   └── status-timeline-compact.blade.php ← Compact timeline variant
│       ├── customer/
│       │   ├── dashboard.blade.php           ← Customer dashboard (active + past orders)
│       │   └── track.blade.php               ← Public order tracking page
│       ├── layouts/
│       │   ├── admin.blade.php               ← Admin layout wrapper
│       │   ├── app.blade.php                 ← Main app layout
│       │   ├── guest.blade.php               ← Guest/auth layout
│       │   └── navigation.blade.php          ← Shared navigation bar
│       ├── receipts/
│       │   └── pdf.blade.php                 ← 80mm thermal receipt template
│       ├── staff/
│       │   ├── dashboard.blade.php           ← Staff dashboard (categorized queues)
│       │   ├── _order-card.blade.php         ← Reusable order card partial
│       │   └── orders/
│       │       ├── create.blade.php          ← New order form (staff-side)
│       │       ├── edit.blade.php            ← Edit order / record payment
│       │       └── search.blade.php          ← Search orders view
│       └── welcome.blade.php                 ← Public landing page
├── routes/
│   ├── web.php                               ← All web routes (public, auth, role-guarded)
│   └── console.php                           ← Artisan commands (default only)
├── tests/                                    ← PHPUnit test stubs
├── composer.json                             ← PHP dependencies
├── package.json                              ← JS dependencies (Vite, Tailwind, etc.)
├── vite.config.js                            ← Vite build configuration
├── phpunit.xml                               ← PHPUnit configuration
└── INSTALL.md                                ← Setup guide & credentials
```

### Folder Purpose Summary

| Folder                     | Purpose                                                                      |
|----------------------------|------------------------------------------------------------------------------|
| `app/Filament/`            | Admin panel resources and dashboard widgets (auto-CRUD powered by Filament)  |
| `app/Http/Controllers/`    | Request handling logic, organized by role (Admin, Staff, Customer, Auth, Api) |
| `app/Http/Middleware/`      | Custom middleware — `RoleMiddleware` enforces role-based access               |
| `app/Jobs/`                | Background queue jobs (notification dispatch)                                |
| `app/Models/`              | Eloquent models representing database tables                                 |
| `app/Notifications/`       | Notification classes for email + database channels                           |
| `app/Services/`            | Reusable business logic (pricing, ticket generation)                         |
| `app/Providers/`           | Service & panel providers (Filament configuration lives here)                |
| `database/migrations/`     | Database schema definitions                                                  |
| `database/seeders/`        | Sample data seeders for development/demo                                     |
| `resources/views/`         | Blade templates organized by role/feature                                    |
| `routes/`                  | Route definitions (web routes in `web.php`)                                  |
| `public/`                  | Publicly accessible files (entry point, PWA manifest, icons)                 |
| `config/`                  | Laravel configuration files                                                  |

---

## 3. Key Files and Their Roles

| # | File Path | Purpose | Key Classes / Functions | Modification Notes |
|---|-----------|---------|-------------------------|--------------------|
| 1 | `app/Models/Order.php` | Core order model — statuses, payment, relationships | `Order` class, `STATUSES` constant, `customer()`, `staff()`, `items()`, `isPaid()` | Edit here to add new order statuses, fields, or business rules |
| 2 | `app/Models/OrderItem.php` | Line item for each order (cloth type + service + weight) | `OrderItem` class, `SERVICE_TYPES` constant, `order()` | Edit to add new service types (e.g., "iron") |
| 3 | `app/Models/Setting.php` | Singleton shop configuration (name, address, pricing) | `Setting::instance()`, `getPriceForService()`, cached via `Cache::remember()` | Edit to add new configurable settings (e.g., operating hours) |
| 4 | `app/Models/User.php` | User model with role-based helpers | `isAdmin()`, `isStaff()`, `isCustomer()`, `orders()`, `assignedOrders()` | Add new roles here; also update `RoleMiddleware` |
| 5 | `app/Services/TicketNumberService.php` | Generates sequential ticket numbers: `GW-2026-0001` | `generate()` — uses DB lock to prevent duplicates | Change ticket prefix or format here |
| 6 | `app/Services/PriceCalculatorService.php` | Calculates subtotals and order totals | `calculateItemSubtotal()`, `calculateOrderTotal()` | Add discounts or surcharge logic here |
| 7 | `app/Http/Controllers/Staff/OrderController.php` | Staff order management (create, status update, payment, approve) | `store()`, `updateStatus()`, `updatePayment()`, `approve()`, `search()`, `repeat()` | Most order workflow changes go here |
| 8 | `app/Http/Controllers/Customer/OrderRequestController.php` | Customer self-service order submission | `store()` — validates, calculates price, creates order as "pending_approval" | Modify customer submission flow or validation here |
| 9 | `app/Http/Controllers/Admin/SalesController.php` | Sales reporting with PDF & CSV export | `index()`, `exportPdf()`, `exportCsv()` — uses DB aggregates & streaming CSV | Add new report formats or filters here |
| 10 | `app/Http/Controllers/Admin/DashboardController.php` | Admin dashboard with revenue stats | `index()` — single aggregate queries for today + all-time stats | Customize admin dashboard metrics here |
| 11 | `routes/web.php` | All application routes (public, auth, role-guarded) | Route groups: `customer.*`, `staff.*`, `admin.*` | Add new pages/endpoints here |
| 12 | `app/Filament/Resources/OrderResource.php` | Filament admin CRUD for orders | `form()`, `table()`, `updateStatus` action | Customize admin panel order management here |
| 13 | `app/Notifications/OrderReadyNotification.php` | Notifies customer when laundry is ready | `via()`, `toMail()`, `toArray()` — database + email channels | Enable Twilio SMS by uncommenting channel |
| 14 | `resources/views/welcome.blade.php` | Public landing page with branding, services, CTA | HTML/Blade — hero section, service cards, animated SVG building | Rebrand or change copy here |
| 15 | `database/seeders/DatabaseSeeder.php` | Seeds demo data: admin, staff, customers, 25 orders | Creates all test users and sample orders | Modify to change seed data for demos |

### Representative Code Snippets

**Ticket Number Generation** (`app/Services/TicketNumberService.php`):
```php
public function generate(): string
{
    return DB::transaction(function () {
        $year   = date('Y');
        $prefix = "GW-{$year}-";
        $lastOrder = Order::withTrashed()
            ->where('ticket_number', 'like', $prefix . '%')
            ->orderByDesc('ticket_number')
            ->lockForUpdate()->first();
        $nextNumber = $lastOrder
            ? (int) substr($lastOrder->ticket_number, strlen($prefix)) + 1
            : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    });
}
```

**Price Calculation** (`app/Services/PriceCalculatorService.php`):
```php
public function calculateItemSubtotal(string $serviceType, float $weight): array
{
    $settings   = Setting::instance();
    $pricePerKg = $settings->getPriceForService($serviceType);
    $subtotal   = round($pricePerKg * $weight, 2);
    return ['price_per_kg' => $pricePerKg, 'subtotal' => $subtotal];
}
```

**Dual Authentication** (`app/Http/Controllers/Auth/LoginController.php`):
```php
$login = $request->input('login');
$field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
Auth::attempt([$field => $login, 'password' => $request->password]);
```

---

## 4. Architecture and Data Flow

### 4.1 Overall Request Flow

```
1. User visits URL in browser
2. Laravel Router (routes/web.php) matches the URL to a controller action
3. Middleware pipeline executes:
   a. Standard Laravel middleware (session, CSRF, etc.)
   b. RoleMiddleware checks user role (admin/staff/customer)
4. Controller processes the request:
   a. Uses Services (PriceCalculator, TicketNumber) for business logic
   b. Interacts with Models (Order, OrderItem, User, Setting)
   c. May dispatch Jobs (SendOrderReadyNotification) to the queue
5. Controller returns a Blade view (HTML response) or redirect
6. Blade templates render using Tailwind CSS + Alpine.js for interactivity
```

### 4.2 Order Lifecycle Flow

```
                         ┌──────────────────┐
                         │  Customer submits │
                         │  laundry request  │
                         └────────┬─────────┘
                                  ▼
                      ┌───────────────────────┐
                      │   Pending Approval    │  ← Customer-submitted orders start here
                      └───────────┬───────────┘
                                  │ Staff approves
                                  ▼
                      ┌───────────────────────┐
                      │      Received         │  ← Staff-created orders start here
                      └───────────┬───────────┘
                                  ▼
                      ┌───────────────────────┐
                      │      Washing          │
                      └───────────┬───────────┘
                                  ▼
                      ┌───────────────────────┐
                      │      Drying           │
                      └───────────┬───────────┘
                                  ▼
                      ┌───────────────────────┐
                      │      Folding          │
                      └───────────┬───────────┘
                                  ▼
                      ┌───────────────────────┐
                      │  Ready for Pickup     │  ← Notification sent to customer
                      └───────────┬───────────┘
                                  ▼
                      ┌───────────────────────┐
                      │     Collected         │  ← Payment recorded, receipt available
                      └───────────────────────┘

   At any point before approval:
                      ┌───────────────────────┐
                      │     Cancelled         │  ← Customer may cancel pending orders
                      └───────────────────────┘
```

### 4.3 Core Module Breakdown

#### A. Frontend / UI Layer
- **Templates**: Blade views in `resources/views/`, organized by role (`admin/`, `staff/`, `customer/`, `auth/`)
- **Styling**: Tailwind CSS v4, compiled via Vite
- **Interactivity**: Alpine.js for client-side behavior (dropdowns, modals, live calculations)
- **Components**: Reusable Blade components in `resources/views/components/` (status badges, timelines, toasts)
- **Layouts**: Three layout files — `app.blade.php` (authenticated), `guest.blade.php` (login/register), `admin.blade.php` (admin pages)
- **Navigation**: Fixed top navbar with role-aware links (`navigation.blade.php`)

#### B. Business Logic Layer
- **Controllers**: Organized by role — each role has its own namespace (`Admin\`, `Staff\`, `Customer\`)
- **Services**: Extracted reusable logic into dedicated service classes:
  - `PriceCalculatorService` — price × weight calculations
  - `TicketNumberService` — sequential, collision-safe ticket generation
- **Role Middleware**: `RoleMiddleware` enforces access control at the route level

#### C. Data Layer (Models + Database)
- **User** — Stores all users with a `role` field (`admin`, `staff`, `customer`). Customers log in by phone; staff/admin by email.
- **Order** — Central entity with ticket number, status progression, weight, pricing, and payment fields. Uses soft deletes.
- **OrderItem** — Each order can have multiple items (different cloth types and service types).
- **Setting** — Singleton row storing shop configuration and per-kg service pricing. Cached for 1 hour.

#### D. Notification & Queue Layer
- **Notifications**: Three notification classes for order lifecycle events (approved, cancelled, ready)
- **Channels**: `database` (always) + `mail` (if user has email)
- **Queue Job**: `SendOrderReadyNotification` — dispatched asynchronously when order status changes to "ready for pickup"
- **Queue Driver**: Database (configurable in `.env`)

#### E. Admin Panel (Filament)
- Accessible at `/panel` — separate from the main Blade-based admin views
- **Resources**: `OrderResource`, `UserResource`, `SettingResource` — auto-generated CRUD
- **Widgets**: `StatsOverview`, `OrdersChart`, `RevenueByMethodChart`, `RecentOrdersTable`
- Configured in `AdminPanelProvider.php`

### 4.4 Database Schema

```
┌──────────┐       ┌──────────────┐       ┌──────────────┐
│  users   │       │    orders    │       │ order_items   │
├──────────┤       ├──────────────┤       ├──────────────┤
│ id       │──┐    │ id           │──┐    │ id           │
│ name     │  │    │ ticket_number│  │    │ order_id (FK)│
│ email    │  ├──◄ │ customer_id  │  ├──◄ │ cloth_type   │
│ phone    │  │    │ staff_id     │  │    │ weight       │
│ role     │  │    │ status       │  │    │ service_type │
│ password │  │    │ total_weight │  │    │ price_per_kg │
└──────────┘  │    │ total_price  │  │    │ subtotal     │
              │    │ payment_*    │  │    └──────────────┘
              │    │ notes        │  │
              │    │ estimated_*  │  │    ┌──────────────┐
              │    │ requested_*  │  │    │  settings    │
              │    └──────────────┘  │    ├──────────────┤
              │                      │    │ shop_name    │
              │    ┌──────────────┐  │    │ shop_address │
              │    │notifications │  │    │ shop_phone   │
              │    ├──────────────┤  │    │ wash_price   │
              └──◄ │ notifiable_id│  │    │ dry_price    │
                   │ type         │  │    │ fold_price   │
                   │ data (JSON)  │  │    │ gcash_number │
                   └──────────────┘  │    │ qr_code_path │
                                     │    └──────────────┘
                   ┌──────────────┐  │
                   │    jobs      │  │
                   ├──────────────┤  │
                   │ queue        │──┘
                   │ payload      │ (queue-based notification/job processing)
                   └──────────────┘
```

---

## 5. Dependencies and Setup

### 5.1 PHP Dependencies (composer.json)

| Package                        | Role                                                        |
|--------------------------------|-------------------------------------------------------------|
| `laravel/framework` ^12.0     | Core web framework (routing, ORM, auth, queues, etc.)       |
| `filament/filament` 3.3       | Admin panel with auto-generated CRUD, charts, and widgets   |
| `barryvdh/laravel-dompdf` ^3.1| PDF generation (sales reports, receipts)                    |
| `spatie/laravel-permission` 6.24| Role & permission management                               |
| `laravel/tinker` ^2.10        | Interactive REPL for debugging                              |

**Dev Dependencies:**

| Package                        | Role                                              |
|--------------------------------|---------------------------------------------------|
| `fakerphp/faker`               | Generates fake data for seeders/tests             |
| `laravel/pail`                 | Real-time log viewer in terminal                  |
| `laravel/pint`                 | Code style fixer (PSR-12)                         |
| `laravel/sail`                 | Docker development environment                    |
| `mockery/mockery`              | Test mocking library                              |
| `nunomaduro/collision`         | Better error output in CLI                        |
| `phpunit/phpunit`              | Testing framework                                 |

### 5.2 JavaScript Dependencies (package.json)

| Package                  | Role                                          |
|--------------------------|-----------------------------------------------|
| `tailwindcss` ^4.0      | Utility-first CSS framework                   |
| `@tailwindcss/vite`     | Tailwind CSS Vite integration plugin          |
| `vite` ^7.0             | Frontend build tool (dev server + production) |
| `laravel-vite-plugin`   | Bridges Vite with Laravel's asset system      |
| `axios`                 | HTTP client for AJAX requests                 |
| `concurrently`          | Runs multiple dev processes simultaneously    |

### 5.3 Setup Instructions

**Prerequisites:**
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- SQLite (default) or MySQL 8+

**Quick Start:**

```bash
# 1. Clone the repository
git clone <repository-url>
cd laundry_shop

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. Create environment file
cp .env.example .env
php artisan key:generate

# 5. Run database migrations and seed demo data
php artisan migrate --seed

# 6. Build frontend assets
npm run build        # For production
# OR
npm run dev          # For development with hot reload

# 7. Start the application server
php artisan serve

# 8. (Optional) Start queue worker for notifications
php artisan queue:work
```

**One-command development server** (starts web server + queue worker + Vite simultaneously):
```bash
composer dev
```

Open **http://localhost:8000** in your browser.

### 5.4 Default Login Credentials

| Role     | Login              | Password   |
|----------|--------------------|------------|
| Admin    | admin@laundry.test | password   |
| Staff    | maria@laundry.test | password   |
| Staff    | juan@laundry.test  | password   |
| Customer | 09181111111        | password   |
| Customer | 09182222222        | password   |

> **Note:** Customers log in with their **phone number**, while Staff and Admin log in with their **email address**.

### 5.5 Potential Issues and Fixes

| Issue | Fix |
|-------|-----|
| "Class not found" errors | Run `composer dump-autoload` |
| Blank page after deploy | Run `php artisan optimize:clear` to clear all caches |
| Settings not updating | `Setting::clearCache()` is called automatically; if stale, run `php artisan cache:clear` |
| Notifications not sending | Ensure `QUEUE_CONNECTION=database` in `.env` and run `php artisan queue:work` |
| Vite assets not loading | Run `npm run build` for production, or `npm run dev` for local development |
| SQLite "database does not exist" | Create the file: `touch database/database.sqlite` |

---

## 6. How to Make Modifications

### 6.1 Common Changes Guide

#### Change Service Pricing
1. **Via Admin Panel** (no code changes): Log in as Admin → Navigate to `/panel` → Settings → Edit shop settings → Update prices.
2. **Via Code (default values)**: Edit `database/seeders/SettingSeeder.php` and change `wash_price`, `dry_price`, `fold_price`.
3. **Where prices are used**: `app/Models/Setting.php` → `getPriceForService()`.

#### Add a New Service Type (e.g., "Iron")
1. Add price field to `settings` migration and `Setting` model.
2. Add `'iron'` to `OrderItem::SERVICE_TYPES` in `app/Models/OrderItem.php`.
3. Update `Setting::getPriceForService()` to include the new `'iron'` case.
4. Update the order form in `app/Filament/Resources/OrderResource.php` (Filament panel) and `resources/views/staff/orders/create.blade.php` (staff view).

#### Change the Ticket Number Format
Edit `app/Services/TicketNumberService.php` — modify the `$prefix` variable and `str_pad()` parameters.

#### Add a New User Role
1. Add the role string to `User` model role helpers in `app/Models/User.php`.
2. Update `RoleMiddleware.php` to accept the new role.
3. Create new route group in `routes/web.php` with appropriate middleware.
4. Create controller(s) in a new namespace under `app/Http/Controllers/`.
5. Create views in a new folder under `resources/views/`.

#### Customize the Landing Page
Edit `resources/views/welcome.blade.php` — all branding, hero text, service cards, and SVG illustrations are in this single file.

#### Add Email/SMS Notifications
- **Email**: Already implemented. Set `MAIL_*` variables in `.env`.
- **SMS via Twilio**: Uncomment the Twilio channel in `app/Notifications/OrderReadyNotification.php` → `via()` method. Install `laravel-notification-channels/twilio` and set `TWILIO_*` env vars.

#### Edit the Admin Dashboard (Blade-based)
Modify `resources/views/admin/dashboard.blade.php` for the view, and `app/Http/Controllers/Admin/DashboardController.php` for the data.

#### Edit the Admin Panel (Filament-based)
- **Orders CRUD**: `app/Filament/Resources/OrderResource.php`
- **Users CRUD**: `app/Filament/Resources/UserResource.php`
- **Settings CRUD**: `app/Filament/Resources/SettingResource.php`
- **Dashboard Widgets**: `app/Filament/Widgets/` directory
- **Panel Config** (branding, colors, navigation): `app/Providers/Filament/AdminPanelProvider.php`

#### Modify the PDF Receipt Layout
Edit `resources/views/receipts/pdf.blade.php` — this is an 80 mm thermal-style receipt template rendered by DomPDF.

#### Modify the Sales Report PDF
Edit `resources/views/admin/sales-pdf.blade.php`.

### 6.2 Where to Find Things (Quick Reference)

| Want to change...                     | Look here                                                          |
|---------------------------------------|--------------------------------------------------------------------|
| App branding / logo / colors          | `resources/views/welcome.blade.php`, `resources/views/layouts/navigation.blade.php` |
| Service pricing defaults              | `database/seeders/SettingSeeder.php`, or admin panel at runtime    |
| Order statuses                        | `app/Models/Order.php` → `STATUSES` constant                      |
| Order form (staff side)               | `resources/views/staff/orders/create.blade.php`                    |
| Customer order request form           | `resources/views/customer/dashboard.blade.php`                     |
| Payment methods                       | `app/Models/Order.php` → `PAYMENT_METHODS` constant               |
| Navigation links                      | `resources/views/layouts/navigation.blade.php`                     |
| Route URLs                            | `routes/web.php`                                                   |
| Database structure                    | `database/migrations/` directory                                   |
| Email notification content            | `app/Notifications/` directory (each notification class)           |
| Filament admin panel access path      | `app/Providers/Filament/AdminPanelProvider.php` → `->path('panel')`|
| PWA manifest                          | `public/manifest.json`                                             |

### 6.3 Version Control Tips

- Always create a new Git branch before making changes: `git checkout -b feature/my-change`
- After modifying migrations, run `php artisan migrate:fresh --seed` to reset the database
- After changing Blade views, clear the view cache: `php artisan view:clear`
- After modifying configuration, clear config cache: `php artisan config:clear`
- Before deploying, run `npm run build` to compile production assets

---

## 7. Additional Notes

### 7.1 Progressive Web App (PWA)
The application includes a basic PWA setup with:
- `public/manifest.json` — app manifest for "Add to Home Screen"
- `public/sw.js` — service worker
- `public/icons/` — app icons for various devices

### 7.2 Dual Authentication System
The app uses a custom authentication approach:
- **Customers** register and log in with their **Philippine phone number** (format: `09XXXXXXXXX`)
- **Staff and Admin** log in with their **email address**
- The login controller auto-detects whether the input is an email or phone number

### 7.3 Payment Workflow
- Orders can be paid via **Cash**, **GCash**, or **Maya**
- For Cash: the system calculates change automatically (`amount_paid - total_price`)
- For GCash/Maya: customers provide a payment reference number
- The shop's GCash QR code can be uploaded via Admin Settings

### 7.4 Filament Admin Panel vs. Blade Admin Views
The app has **two admin interfaces**:
1. **Blade-based admin views** (at `/admin/*`) — Custom-built dashboard, sales reports, user management, settings
2. **Filament admin panel** (at `/panel`) — Auto-generated CRUD for Orders, Users, and Settings with dashboard widgets

Both are functional. The Blade-based views provide a tailored UX, while Filament provides a quick data management interface.

### 7.5 Notification Channels
| Channel  | Status       | Notes                                    |
|----------|--------------|------------------------------------------|
| Database | Active       | Always enabled; powers in-app bell icon  |
| Email    | Active       | Sends if user has an email address       |
| SMS      | Pre-configured| Uncomment Twilio channel code to enable  |

### 7.6 Performance Considerations
- **Settings caching**: Shop settings are cached for 1 hour to avoid repeated DB queries
- **DB-level aggregates**: Dashboard and sales controllers use `selectRaw()` for efficient aggregate queries
- **Streaming CSV export**: Sales CSV export uses `lazy(200)` cursor for flat memory usage
- **DB transaction locks**: Ticket number generation uses `lockForUpdate()` to prevent race conditions

### 7.7 Security Features
- CSRF protection on all forms
- Password hashing via Laravel's `Hash::make()`
- Role-based access control via `RoleMiddleware`
- Rate limiting on login (5 attempts/minute) and tracking (30 requests/minute)
- Input validation on all controller methods
- Soft deletes on Users and Orders (data is never permanently lost)
- HTTPS forced in production via `AppServiceProvider`

---

*Document generated from codebase analysis — GeloWash Laundry Shop Management System*
*Laravel 12 · Filament 3.3 · Tailwind CSS v4 · PHP 8.2+*
