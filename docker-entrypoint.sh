#!/bin/sh
set -e

# Pastikan direktori storage tersedia (tulis satu per satu agar kompatibel POSIX sh)
mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs
chmod -R 775 storage bootstrap/cache

# Generate key jika belum ada (dipakai saat APP_KEY kosong / tidak diset)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    php artisan key:generate --force
fi

# Link storage (public/storage -> storage/app/public) untuk gambar produk
php artisan storage:link --force || true

# Jalankan migrasi + seed (updateOrCreate, aman dijalankan berulang).
# Seed membuat admin & data produk awal. Jika DB belum siap, fallback ke migrate saja.
php artisan migrate --force --seed || php artisan migrate --force

# Bersihkan cache agar konfigurasi env terbaru dipakai
php artisan optimize:clear || true

exec "$@"
