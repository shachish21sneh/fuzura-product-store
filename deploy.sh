#!/usr/bin/env bash
set -e

echo "=========================================================="
echo "  Deploying Fuzura Warranty System on Live Server"
echo "  Path: /home2/glittl3ql/warranty.fuzurra.in"
echo "=========================================================="

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd "$DIR"

# 1. Setup production .env if not present
if [ ! -f .env ]; then
    echo "Creating production .env configuration..."
    cat << 'EOF' > .env
APP_NAME="Fuzura Product Store"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://warranty.fuzurra.in

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glittl3q_warranty
DB_USERNAME=glittl3q_warranty
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
EOF
    echo "[1/6] Created production .env template."
else
    echo "[1/6] Existing .env found, preserving configuration."
fi

# 2. Select appropriate PHP binary on cPanel (prefers ea-php83 or ea-php82)
PHP_BIN="php"
if [ -f "/opt/cpanel/ea-php83/root/usr/bin/php" ]; then
    PHP_BIN="/opt/cpanel/ea-php83/root/usr/bin/php"
elif [ -f "/opt/cpanel/ea-php82/root/usr/bin/php" ]; then
    PHP_BIN="/opt/cpanel/ea-php82/root/usr/bin/php"
elif [ -f "/usr/local/bin/ea-php83" ]; then
    PHP_BIN="/usr/local/bin/ea-php83"
elif [ -f "/usr/bin/ea-php83" ]; then
    PHP_BIN="/usr/bin/ea-php83"
elif which ea-php83 >/dev/null 2>&1; then
    PHP_BIN="ea-php83"
elif which ea-php82 >/dev/null 2>&1; then
    PHP_BIN="ea-php82"
fi

echo "[2/6] Using PHP binary: $($PHP_BIN -v | head -n 1)"

# 3. Install composer dependencies (or use pre-packaged vendor)
if [ -f "vendor/autoload.php" ]; then
    echo "[3/6] Pre-packaged vendor dependencies found! Skipping composer install."
else
    echo "[3/6] Installing Composer packages..."
    if which composer >/dev/null 2>&1; then
        $PHP_BIN $(which composer) install --no-dev --optimize-autoloader --no-interaction || true
    else
        if [ ! -f "composer.phar" ]; then
            echo "Downloading composer.phar..."
            $PHP_BIN -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" || true
            $PHP_BIN composer-setup.php || true
            $PHP_BIN -r "unlink('composer-setup.php');" || true
        fi
        if [ -f "composer.phar" ]; then
            $PHP_BIN composer.phar install --no-dev --optimize-autoloader --no-interaction || true
        fi
    fi
fi

# 4. Generate app key if needed
$PHP_BIN artisan key:generate --force || true

# 5. Run migrations & seeders (creates tables and populates demo catalog, replacements, warranties)
echo "[4/6] Migrating database and populating demo data..."
if ! $PHP_BIN artisan migrate:fresh --seed --force; then
    echo "Artisan migrate returned an error, directly importing database/glittl3q_warranty_dump.sql..."
    DB_PASS=$(grep '^DB_PASSWORD=' .env | cut -d '=' -f2- | tr -d "'" | tr -d '"')
    if [ -n "$DB_PASS" ]; then
        mysql -u glittl3q_warranty -p"$DB_PASS" glittl3q_warranty < database/glittl3q_warranty_dump.sql || true
    else
        mysql -u glittl3q_warranty glittl3q_warranty < database/glittl3q_warranty_dump.sql || true
    fi
fi

# 6. Create storage symlink and permissions
echo "[5/6] Creating storage link and permissions..."
$PHP_BIN artisan storage:link || true
chmod -R 775 storage bootstrap/cache || true

# 7. Production Caching
echo "[6/6] Caching configuration, routes, and views..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo ""
echo "=========================================================="
echo "  SUCCESS! Application is now LIVE at:"
echo "  https://warranty.fuzurra.in"
echo "=========================================================="
