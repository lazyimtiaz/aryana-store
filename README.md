# ARANYA Skin & Beauty

A Laravel-based e-commerce application for a fictional skincare, haircare, body care, and beauty retailer. ARANYA combines a customer-facing storefront with an administrative dashboard for managing products, categories, brands, orders, and other store content.

The project is built with Laravel, Blade, Tailwind CSS, Alpine.js, MySQL, Laravel Breeze, and Filament. It is intended as a portfolio project and a foundation for further e-commerce development.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Application Structure](#application-structure)
- [Requirements](#requirements)
- [Installation and Setup](#installation-and-setup)
- [Database and Seeders](#database-and-seeders)
- [Running the Application](#running-the-application)
- [Admin Panel](#admin-panel)
- [Important Routes](#important-routes)
- [Frontend Assets](#frontend-assets)
- [File Storage](#file-storage)
- [Testing and Troubleshooting](#testing-and-troubleshooting)
- [Security Notes](#security-notes)
- [Deployment Considerations](#deployment-considerations)
- [Future Improvements](#future-improvements)
- [License](#license)

## Project Overview

ARANYA Skin & Beauty is a demonstration e-commerce platform designed around a clean, modern beauty-retail experience.

The storefront allows visitors to discover products, browse categories and brands, search the catalogue, and interact with shopping features. Registered users can access account functionality, manage addresses, view orders, maintain a wishlist, and participate in supported shopping workflows.

The same Laravel application includes a Filament administrative panel. Authorized administrators can manage store data and supported content-management features without maintaining a separate backend application.

**Project goals:**

- Build a complete Laravel e-commerce project with a custom storefront.
- Practice database-driven catalogue and shopping functionality.
- Integrate customer authentication and an administrative panel.
- Organize application logic using Laravel models, controllers, routes, migrations, and Blade views.
- Establish a maintainable foundation for future improvements and deployment.

## Features

### Storefront

- Custom ARANYA Skin & Beauty branding and responsive storefront interface.
- Homepage with featured store content.
- Product catalogue and individual product pages.
- Category and brand browsing.
- Product search.
- Sale, new-arrival, and combo-product browsing routes.
- Store information pages, including FAQ and contact pages.
- Newsletter subscription with request throttling and validation.

### Shopping and Checkout

- Shopping cart operations, including adding, updating, and removing items.
- Authenticated checkout workflow.
- Order creation and order-success page.
- Coupon application and removal routes.
- Support for the application's configured product pricing and stock data.

Payment gateway integration, automated fulfilment, and other external services should be considered separate integrations unless explicitly implemented and configured.

### Customer Accounts

- Registration and login through Laravel Breeze.
- Authenticated account dashboard.
- Order history and order details.
- Address management, including setting a default address.
- Profile editing and account-related operations.
- Wishlist functionality.
- Product review submission.

### Administrative Panel

- Filament-based admin interface.
- Role-based eligibility for accessing the admin panel.
- Product, category, and brand administration through the implemented resources.
- Management of supported store content, including FAQs and store pages.
- Store settings management.
- Newsletter subscriber management.
- Additional resource management according to the Filament resources installed in the project.

The `User` model implements Filament's `FilamentUser` contract and permits panel access when the user's role is `admin`.

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.2 or compatible supported version | Application runtime |
| Laravel 12 | Backend framework |
| MySQL | Relational database |
| Blade | Server-rendered templates |
| Tailwind CSS | Styling and responsive layouts |
| Alpine.js | Lightweight frontend interactions |
| Vite | Frontend asset development and bundling |
| Laravel Breeze | Authentication scaffolding |
| Filament 4 | Administrative interface |
| Composer | PHP dependency management |
| npm | Frontend dependency management |

Check `composer.json`, `package.json`, and the lock files for the exact dependency versions installed in your checkout.

## Application Structure

Important directories and files include:

```text
aranya/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
│   └── Filament/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
│   ├── build/
│   └── storage/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

This is a high-level guide rather than an exhaustive inventory. The exact Filament resource and view directories depend on the current project structure.

### Important application files

- `routes/web.php` — storefront, cart, checkout, account, and content routes.
- `routes/auth.php` — authentication routes supplied by the Breeze setup.
- `app/Models/User.php` — user roles, relationships, password casting, and Filament access eligibility.
- `database/migrations/` — database schema definitions.
- `database/seeders/` — sample data and development account creation.
- `resources/views/` — Blade templates for the storefront and other server-rendered pages.
- `resources/css/app.css` and `resources/js/app.js` — frontend entry points.
- `bootstrap/app.php` — Laravel application bootstrap and middleware configuration.

## Requirements

Install the following before setting up the project:

- PHP 8.2 or another version compatible with the installed Laravel dependencies.
- Composer.
- Node.js and npm.
- MySQL.
- Git.
- PhpStorm or another editor.
- A PHP environment with the extensions required by Laravel and the installed Composer packages.

If you already have a working Laravel development environment on Windows, you can use the same PHP, Composer, Node.js, and MySQL installations.

## Installation and Setup

### 1. Clone the repository

After publishing the repository on GitHub, clone it:

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd <YOUR_PROJECT_DIRECTORY>
```

Replace the placeholders with your actual repository URL and directory name.

### 2. Install PHP dependencies

```bash
composer install
```

Composer installs the dependencies defined in `composer.json` and `composer.lock`.

### 3. Create the environment configuration

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Edit `.env` and configure the application environment and database connection.

Example configuration for a local MySQL database:

```dotenv
APP_NAME="ARANYA Skin & Beauty"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aranya
DB_USERNAME=root
DB_PASSWORD=
```

These are example local settings. Use the actual credentials and database name from your development environment. Do not commit your real `.env` file.

### 4. Generate the application key

```bash
php artisan key:generate
```

Laravel uses the application key for encryption and related framework functionality.

### 5. Create the database

Create a MySQL database named `aranya`, or choose another name and update `DB_DATABASE` in `.env`.

For example, using the MySQL command line:

```sql
CREATE DATABASE aranya
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Make sure the database user specified in `.env` has permission to access the database.

### 6. Run database migrations

```bash
php artisan migrate
```

This creates the database tables defined by the project's migrations.

### 7. Seed sample data

Inspect the seeders before running them, especially `UserSeeder.php` and `DatabaseSeeder.php`.

If the seeders contain only appropriate development data, run:

```bash
php artisan db:seed
```

Alternatively, to rebuild a disposable local database from scratch:

```bash
php artisan migrate:fresh --seed
```

**Warning:** `migrate:fresh` drops all existing tables before rebuilding the schema. Never run it against a database containing data you need to preserve.

The current development `UserSeeder` creates sample administrator and customer accounts. Its hardcoded demo password must be replaced or safely configured before exposing the application publicly. Do not reuse demo credentials in production.

### 8. Install frontend dependencies

```bash
npm install
```

### 9. Build the frontend assets

```bash
npm run build
```

This compiles the frontend assets for normal application use.

During frontend development, you can instead run:

```bash
npm run dev
```

Keep the development server running while working on the frontend.

### 10. Create the public storage link

```bash
php artisan storage:link
```

This creates the public storage link used for files stored through Laravel's public disk, provided the hosting environment supports symbolic links.

### 11. Clear cached configuration

```bash
php artisan optimize:clear
```

This clears Laravel's cached configuration, routes, views, and other optimization caches.

## Running the Application

Start Laravel's local development server:

```bash
php artisan serve
```

Open the URL shown in your terminal. The default is:

```text
http://127.0.0.1:8000
```

If you are also using the Vite development server, run `npm run dev` in a separate terminal.

## Admin Panel

The Filament admin panel is part of the same Laravel application as the storefront.

Default local panel URL:

```text
http://127.0.0.1:8000/admin
```

Access requires a user account that satisfies the panel's authorization rules. In the current implementation, `User::canAccessPanel()` permits access when the user's role is `admin`.

To configure an administrator, use the project's development seeder or create an account through a secure, controlled administrative process.

Before public deployment:

- Replace hardcoded demo credentials.
- Create a unique administrator password.
- Verify that ordinary customer accounts cannot access the admin panel.
- Keep `APP_DEBUG=false`.
- Use valid production environment settings.
- Do not expose the application's `.env` file or production database credentials.

## Important Routes

The following routes are defined in `routes/web.php`. Actual access depends on the middleware and controller logic.

| Route | Purpose |
|---|---|
| `/` | Storefront homepage |
| `/shop` | Product catalogue |
| `/product/{product:slug}` | Product details |
| `/category/{category:slug}` | Category details |
| `/brand/{brand:slug}` | Brand details |
| `/brands` | Brand listing |
| `/search` | Product search |
| `/sale` | Sale products |
| `/new-arrivals` | New arrivals |
| `/combos` | Combo products |
| `/cart` | Shopping cart |
| `/checkout` | Checkout |
| `/account` | Customer dashboard |
| `/account/orders` | Customer order history |
| `/account/addresses` | Address management |
| `/account/profile` | Profile management |
| `/wishlist` | Wishlist |
| `/faq` | FAQ page |
| `/contact` | Contact page |
| `/admin` | Filament administration panel |

The project also defines routes for newsletter subscriptions, coupon operations, order details, address updates, and product reviews.

To inspect all registered routes, run:

```bash
php artisan route:list
```

## Frontend Assets

The frontend uses Blade templates, Tailwind CSS, Alpine.js, and Vite.

For local development:

```bash
npm run dev
```

For a production asset build:

```bash
npm run build
```

If styles or JavaScript do not appear correctly, verify that the required assets are present and that the Blade layout loads the correct Vite entry points.

Do not commit `node_modules/`. The dependency manifests and lock files should be committed so another developer can install the same frontend dependencies.

## File Storage

Product images and other uploaded files may be stored through Laravel's filesystem configuration.

For local development:

1. Check the `FILESYSTEM_DISK` setting in `.env`.
2. Verify that the configured upload disk is writable.
3. Run `php artisan storage:link` when using Laravel's public disk.
4. Confirm that image URLs reference the expected public storage location.

For production, confirm that uploaded files persist across deployments. Some hosting environments use ephemeral storage, meaning uploaded files can disappear when an instance is replaced. In such environments, use a supported persistent volume or external object storage.

## Testing and Troubleshooting

### Run automated tests

```bash
php artisan test
```

The available tests depend on the current contents of the project's `tests/` directory.

### Clear Laravel caches

```bash
php artisan optimize:clear
```

### Rebuild frontend assets

```bash
npm run build
```

### Database connection errors

Verify:

- The MySQL service is running.
- The database exists.
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` are correct.
- The configured database user has the required permissions.

After changing `.env`, clear the configuration cache if necessary.

### Missing application key

Run:

```bash
php artisan key:generate
```

Do not regenerate the key on an established production installation without understanding the consequences for encrypted data and sessions.

### Missing uploaded images

Check the filesystem disk, storage permissions, public storage link, and whether the uploaded files exist in the expected location.

### Missing CSS or JavaScript

Run:

```bash
npm install
npm run build
```

Then verify the Vite asset references in the relevant Blade layout.

### Admin panel access problems

Check that the administrator's database record has `role = admin` and that the Filament panel configuration and `canAccessPanel()` implementation are correct.

## Security Notes

- Never commit `.env`, private credentials, production database dumps, or real customer data.
- Do not publish or reuse default administrator passwords.
- Keep production debug mode disabled.
- Validate and authorize sensitive operations on the server.
- Ensure users can access only their own orders, addresses, and other private records.
- Protect administrative operations with server-side authorization.
- Apply appropriate request throttling to sensitive or abuse-prone endpoints.
- Keep Composer and npm dependencies maintained.
- Back up production data before performing migrations or destructive database operations.

The public GitHub repository should contain source code and safe example configuration—not production secrets.

## Deployment Considerations

ARANYA requires a hosting environment compatible with Laravel and its dependencies. A suitable deployment must provide:

- A supported PHP runtime and required extensions.
- Composer dependency installation.
- A compatible MySQL database.
- A writable Laravel storage directory.
- A persistent strategy for uploaded files.
- A web server configured to serve Laravel's `public/` directory.
- HTTPS and production environment variables.
- A process for running migrations and building frontend assets.

The storefront and Filament admin panel are part of the same application, so they do not require separate hosting projects.

Free hosting platforms can impose storage, execution-time, database, or resource restrictions. Verify Laravel compatibility and persistence before deploying. A free subdomain can be used instead of purchasing a custom domain.

## Future Improvements

Potential future enhancements include:

- Production-ready payment gateway integration.
- Automated order and shipping notifications.
- Improved order status and inventory workflows.
- Product filtering and sorting enhancements.
- Additional product and category management tools.
- Improved customer account and order-history UX.
- Expanded automated tests.
- Email delivery configuration.
- Persistent or external image storage for production.
- Accessibility, SEO, and performance improvements.

These are potential improvements, not claims that every feature is already implemented.

## License

No license has been specified for this repository yet.

If you intend to let others use, modify, or redistribute the project, add an appropriate `LICENSE` file and update this section. Without an explicit license, ordinary copyright restrictions generally apply.
