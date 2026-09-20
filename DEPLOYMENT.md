# Deployment Guide - Apache Server & MySQL 8+

This guide covers deploying the **Fuzura Product Registration & Warranty Management System** on a standard **Apache Web Server + PHP 8.3+ + MySQL 8+** hosting environment (including XAMPP, cPanel, or standard Linux/Windows Apache servers).

---

## 1. System Requirements

* **PHP Version**: PHP 8.3 or higher
* **Required PHP Extensions**:
  - `pdo_mysql`
  - `openssl`
  - `mbstring`
  - `curl`
  - `fileinfo`
  - `gd`
  - `zip`
* **Web Server**: Apache 2.4+ with `mod_rewrite` enabled
* **Database**: MySQL 8.0+ or MariaDB 10.4+

---

## 2. Apache VirtualHost Configuration

### VirtualHost Setup (Recommended)
Point Apache's `DocumentRoot` directly to the `public` directory of the application:

```apache
<VirtualHost *:80>
    ServerName fuzurastore.local
    ServerAlias www.fuzurastore.local
    DocumentRoot "C:/xampp/htdocs/fuzura-product-store/public"

    <Directory "C:/xampp/htdocs/fuzura-product-store/public">
        Options -Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/fuzura_error.log"
    CustomLog "logs/fuzura_access.log" combined
</VirtualHost>
```

> **Note for XAMPP users**:
> Add the snippet above to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`, and add `127.0.0.1 fuzurastore.local` to your `hosts` file (`C:\Windows\System32\drivers\etc\hosts`).

### Shared Hosting (Single Root Directory)
If your hosting does not allow changing DocumentRoot, the included root `.htaccess` will automatically rewrite all requests into `public/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 3. Database & Environment Configuration

1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Open `.env` and set your MySQL credentials:
   ```env
   APP_NAME="Fuzura Product Store"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://fuzurastore.local

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fuzura_product_store
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password

   FILESYSTEM_DISK=public
   ```

3. Create the database in MySQL:
   ```sql
   CREATE DATABASE IF NOT EXISTS `fuzura_product_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

---

## 4. Initial Setup & Migrations

Run the following commands in the project directory:

```bash
# Generate application encryption key
php artisan key:generate

# Run migrations and seed demo data
php artisan migrate --seed

# Create storage symlink for uploaded invoice bills
php artisan storage:link
```

---

## 5. Directory Permissions

Ensure the web server user (`www-data`, `daemon`, or Apache user) has read and write permissions on:
- `storage/`
- `storage/app/public/bills/`
- `storage/framework/`
- `storage/logs/`
- `bootstrap/cache/`

On Linux/cPanel:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 6. Production Performance Optimizations

Run the following caching commands to maximize performance in production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Pre-Seeded Demonstration Accounts

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Super Administrator** | `admin@fuzura.com` | `password123` | `/admin/login` |
| **Demo Customer 1** | `john.doe@example.com` | `password123` | `/customer/login` |
| **Demo Customer 2** | `jane.smith@example.com` | `password123` | `/customer/login` |
| **Demo Customer 3** | `michael.brown@example.com` | `password123` | `/customer/login` |

### Sample Serial Numbers for Testing:
- `FZ-SN-1002`: Mid-chain replacement unit (tracing back to `FZ-SN-1001` and forward to `FZ-SN-1004`).
- `FZ-SN-1004`: Current active unit in the 3-level chain.
- `FZ-SN-2001`: Active registered warranty with remaining days countdown.
- `FZ-SN-3001`: Expired warranty demonstration.
- `FZ-SN-1005`: Available in-stock unit ready for sale.
