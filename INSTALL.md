# FreshKlean Laundry – Full-Stack Laravel Application

A production-ready laundry shop management system with **3 user roles** (Admin, Staff, Customer), digital laundry tickets, auto price calculator, real-time status tracking, and daily sales reporting.

---

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ & npm
- SQLite (default) or MySQL 8+

---

## Quick Start (5 minutes)

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Create environment file
cp .env.example .env
php artisan key:generate

# 3. Run migrations & seed sample data
php artisan migrate --seed

# 4. Build frontend assets
npm run build     # production
npm run dev       # development (with hot reload)

# 5. Start the server
php artisan serve
```

Open **http://localhost:8000** and you're live!

---

## Default Login Credentials

| Role     | Email / Phone       | Password   |
|----------|---------------------|------------|
| Admin    | admin@laundry.test  | password   |
| Staff    | maria@laundry.test  | password   |
| Staff    | juan@laundry.test   | password   |
| Customer | 09181111111         | password   |
| Customer | 09182222222         | password   |

> **Customers** log in with **phone number + password**.
> **Staff & Admin** log in with **email + password**.

---

## Features

| Feature | Description |
|---------|-------------|
| 🎫 Digital Laundry Tickets | Auto-generated ticket numbers (LAUN-2026-0001) |
| 💰 Auto Price Calculator | Live subtotal calculation by weight × service price |
| 📊 Real-Time Status Tracker | 6-step visual timeline (Received → Completed) |
| 🔔 Notifications | In-app + email notifications when laundry is ready |
| 💳 Payment Recording | Cash / GCash / Maya with change calculation |
| 📋 Staff Dashboard | Quick-action cards to manage daily workflow |
| 📈 Daily Sales & Reports | Date-range filtering with PDF & CSV export |
| 👥 Customer History | Full order history with repeat-order capability |
| ⚙️ Shop Settings | Configurable shop info & pricing from admin panel |
| 🧾 PDF Receipts | Thermal-style 80mm receipts via DomPDF |

---

## User Roles & Permissions

### Admin
- Full dashboard with revenue metrics & payment breakdown
- Daily/weekly/monthly sales reports (PDF & CSV export)
- Manage all users (create, edit, delete staff & customers)
- Configure shop settings & service pricing

### Staff
- Process new laundry orders with live price calculator
- Update order status through the 6-step workflow
- Record payments (cash with change calculation, GCash, Maya)
- Search orders by ticket number, customer name, phone, or status
- Download & print PDF receipts

### Customer
- Self-register with phone number
- Track current orders with visual status timeline
- View order history
- Public ticket tracking (no login required)

---

## Environment Variables

Add these to your `.env` file for optional features:

```env
# SMS Notifications (Twilio) — optional
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_PHONE_NUMBER=+1234567890

# Mail (for email notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null

# Queue driver (use 'database' for async notifications)
QUEUE_CONNECTION=database
```

If using **database queue**, run the worker:

```bash
php artisan queue:work
```

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/            # Register & Login
│   ├── Customer/        # Dashboard & Order Tracking
│   ├── Staff/           # Order CRUD & Dashboard
│   ├── Admin/           # Sales, Users, Settings
│   ├── Api/             # AJAX customer lookup
│   └── ReceiptController.php
├── Models/              # User, Order, OrderItem, Setting
├── Services/            # TicketNumberService, PriceCalculatorService
├── Jobs/                # SendOrderReadyNotification
├── Notifications/       # OrderReadyNotification
└── Http/Middleware/      # RoleMiddleware
```

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Tailwind CSS v4, Alpine.js, Vite
- **PDF:** barryvdh/laravel-dompdf
- **SMS:** Twilio (optional, pre-configured)
- **Auth:** Custom dual-auth (phone for customers, email for staff/admin)

---

## Useful Commands

```bash
# Fresh install with seed data
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# Run queue worker
php artisan queue:work

# Run tests
php artisan test
```

---

## License

MIT
