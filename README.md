# RocketDataHub

A modern, automated data bundle vending platform built for Ghana. RocketDataHub provides instant, affordable data top-ups for MTN, Telecel, and AirtelTigo networks with real-time fulfillment powered by a flexible API integration engine.

## Features

- ⚡ **Instant Fulfillment** — Orders are processed and delivered the moment payment is confirmed
- 🔒 **Secure Payments** — Paystack-powered wallet deposits with industry-standard encryption
- 📊 **Smart Analytics** — Real-time charts, order history, and spending reports
- 🌍 **Ghana-First** — Full support for MTN, Telecel, and AirtelTigo with localized prefix validation
- 👥 **Agent Ecosystem** — Reseller/agent tools with commission tracking
- 🔌 **API Ready** — Documented REST API for third-party integrations
- 🔄 **Bulk Orders** — Purchase data for multiple recipients in a single request

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + Alpine.js + Tailwind CSS
- **Payments**: Paystack
- **PDF**: DomPDF (for invoices)
- **Queue**: Database driver

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (default) or MySQL/PostgreSQL

### Installation

```bash
# Clone the repository
git clone <your-repo-url>
cd RocketDataHub

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Development Server

```bash
composer dev
```

Or run each service separately:

```bash
php artisan serve
npm run dev
php artisan queue:work
```

## Configuration

### Payment Gateway

Set your Paystack keys in `.env`:

```env
PAYSTACK_PUBLIC_KEY=pk_test_...
PAYSTACK_SECRET_KEY=sk_test_...
```

See `PAYSTACK_SETUP.md` for a full Paystack configuration guide.

### API Integration

See `API_README.md` for documentation on:
- Connecting external apps to RocketDataHub (inbound API)
- Connecting RocketDataHub to third-party VTU providers (outbound API)
- Webhook configuration

## Project Structure

```
app/
├── Http/Controllers/     # Route controllers
├── Models/               # Eloquent models
├── Services/             # Business logic (API, payment, fulfillment)
├── Jobs/                 # Background jobs
└── Notifications/        # Email/SMS notifications

resources/views/
├── layouts/              # App, dashboard, admin, guest layouts
├── dashboard/            # User dashboard pages
├── admin/                # Admin panel pages
├── partials/             # Navbar, footer
└── components/           # Reusable Blade components
```

## License

MIT
