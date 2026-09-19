# Node image untuk build aset Vite/Tailwind (stage 1)
FROM node:24-alpine AS node-assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Composer image untuk install dependency PHP (stage 2)
FROM composer:2 AS composer-deps

WORKDIR /app
# Salin seluruh project (vendor & node_modules tidak ikut karena .dockerignore)
COPY . /app
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-reqs

# --- Stage final: PHP + Apache runtime ---
FROM php:8.3-apache AS runtime

# Ekstensi PHP yang dibutuhkan (mysql untuk TiDB, gd untuk gambar, mbstring wajib Laravel)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libonig-dev unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql mysqli zip opcache exif mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache (wajib untuk routing Laravel)
RUN a2enmod rewrite headers

# Salin kode aplikasi (source), lalu timpa dengan vendor & aset build dari stage sebelumnya
WORKDIR /var/www/html
COPY . /var/www/html
COPY --from=composer-deps /app/vendor /var/www/html/vendor
COPY --from=node-assets /app/public/build /var/www/html/public/build

# Arahkan DocumentRoot Apache ke folder public Laravel (nilai literal, tanpa env indirection)
RUN sed -ri -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!<Directory /var/www/html/>!<Directory /var/www/html/public/>!' /etc/apache2/apache2.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Izin tulis untuk storage & bootstrap/cache (folder dibuat penuh di entrypoint saat start)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Script entrypoint: jalankan migrasi + seed + storage link saat container start
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
