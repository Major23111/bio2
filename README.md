<div align="center">
  <img src="https://ui-avatars.com/api/?name=Biogenix&background=10b981&color=fff&size=128&rounded=true" alt="Biogenix Logo" width="100"/>
  <h1>Biogenix Corporate Web Platform</h1>
  <p>A comprehensive Laravel-based enterprise web application for Biogenix Inc. Pvt. Ltd. featuring B2B/B2C order management, product cataloging, and automated Proforma Invoice (PI) generation.</p>
</div>

---

## 🚀 Features

- **🛍️ Complete E-Commerce Storefront**: Product catalog, cart system, and secure checkout.
- **🏢 B2B & B2C User Portals**: Specialized interfaces for business clients and retail customers.
- **📄 Automated PI Generation**: Admin interface to dynamically generate and email Proforma Invoices with automated PDF creation.
- **📦 Inventory & Category Management**: Admin dashboard to manage product lots, categories, and SKU tracking.
- **💰 Dynamic Pricing Management**: Custom price mapping and tier management for different user segments.
- **🔐 Role-Based Access Control (RBAC)**: Secure access management with granular permissions.
- **✉️ Automated Notifications**: Real-time email and system notifications.

## 🛠️ Tech Stack

- **Backend**: [Laravel 12.0](https://laravel.com) / PHP 8.2+
- **Frontend**: [Tailwind CSS v4](https://tailwindcss.com) & [Vite 7](https://vitejs.dev/)
- **Authentication**: Laravel Fortify
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **Database**: MySQL / SQLite (via Eloquent ORM)

## ⚙️ Installation & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL or compatible database

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/pravinDatir/biogenix-website.git
   cd biogenix-website
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Update your `.env` file with the correct database credentials and SMTP details for email functionality.*

4. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Development Servers**
   To start both the Laravel backend and Vite frontend concurrently:
   ```bash
   composer run dev
   ```
   Or run them separately:
   ```bash
   php artisan serve
   npm run dev
   ```

## 📂 Project Structure

- `app/Http/Controllers/` - Contains all web request handlers (Admin, B2B, B2C, API).
- `resources/views/` - Blade templates, organized by feature (e.g., `admin`, `userProfile`, `partials`).
- `resources/css/` - Global Tailwind CSS configurations and base styles.
- `app/Services/` - Core business logic encapsulated in service classes (e.g., `ProfileService`, `EmailNotificationService`).

## 👨‍💻 Maintainers
Maintained by the core development team for **Biogenix Inc. Pvt. Ltd.**

---
*Built with ❤️ utilizing the Laravel Framework.*
