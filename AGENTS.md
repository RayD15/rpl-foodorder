# AGENTS.md — RPL2 FoodOrder

## Ringkasan Proyek
Aplikasi pemesanan makanan/minuman berbasis web **mobile-first** untuk tugas KDI RPL (Laravel 13 + Blade + Tailwind CSS).
- **Alur Utama**: Pilih Menu → Keranjang → Checkout (Nama & Kelas) → Konfirmasi → Redirect WhatsApp Admin.
- **Batasan MVP**: Tanpa payment gateway, status/tracking pesanan, riwayat pesanan, login pembeli, maupun penyimpanan pesanan. Pesanan dikirim langsung ke WhatsApp.

## Database
- **Default**: `DB_CONNECTION=sqlite` (file `database/database.sqlite`) — siap jalan tanpa server DB. Tidak ubah default ini untuk demo.
- **Produksi (opsional)**: MySQL/MariaDB via XAMPP (`C:\xampp\mysql\bin`) tersedia di mesin ini, cukup ubah `.env` (DB_CONNECTION=mysql, dsb).
- **Skema**: Hanya tabel `products`, `categories`, `admins`, `settings`, plus tabel internal Laravel (sessions/cache/jobs).
  - `products.category_id` adalah FK ke `categories` (cascade delete). *Jangan buat tabel `orders` / `order_items`* — pesanan dikirim ke WhatsApp, tidak disimpan.
  - `products.status` bernilai `ready` (tersedia) atau `sold_out` (habis). Produk `sold_out` tidak tampil di menu/hero dan tidak bisa dipesan.

## Perintah Kunci
- Jalankan dev server: `php artisan serve` (URL: http://localhost:8000)
- Setup dari nol: `php artisan key:generate` → `php artisan migrate --seed` → `npm install` → `npm run build`
- Build frontend (Tailwind v4 + Vite): `npm run build`. **Jangan** ubah `vite.config.js` memakai plugin fonts jaringan — gunakan font sistem.
- Verifikasi: `php artisan test` (8 feature test) dan `vendor\bin\pint` (formatting).
- Simbol storage untuk upload gambar: `php artisan storage:link` (sudah dibuat; `public/storage` → `storage/app/public`).

## Akun & Konfigurasi Awal (Seeder)
- Admin login: email `admin1@kdi.com`, password `rplkdi1510` (disimpan hashed). Hanya tabel `admins` — tidak ada user/pembeli.
- Nomor WhatsApp admin default: `6281234567890` (db `settings`, key `whatsapp_number`), format internasional tanpa `+`/spasi; bisa diubah lewat menu Pengaturan.

## Keranjang (Cart) — Penting
- Cart disimpan di **localStorage** browser (`key: rpl_cart`), TIDAK di session/server. Untuk mengubah harga/jumlah produk di keranjang, ubah data di `APP.products` (di-embed ke halaman customer melalui View Composer di `AppServiceProvider`).
- Cart otomatis membersihkan produk yang sudah `sold_out` / dihapus dari database (lihat `Cart.clean()` di `resources/js/app.js`).
- Pesan WhatsApp dibuat **client-side** di `resources/js/app.js` (`buildMessage()`): item + total, Nama, Kelas, Catatan. Sebelum membuka WhatsApp, muncul **modal konfirmasi** ringkasan pesanan. Checkout tidak menyimpan apa pun ke server.
- Saat menambahkan produk baru di produk view, foto SVG dapat ditaruh di `public/images/<nama>.svg`; upload via admin disimpan di `storage/app/public/products`.

## Konvensi
- **Mobile-First UX**: card produk 2 kolom, floating/fixed cart, checkout 1 kolom, CTA WhatsApp besar.
- **Dark Mode**: class `dark` di `<html>` disimpan via `localStorage` (`theme`), custom variant `@custom-variant dark` di `resources/css/app.css`.
- **Desain**: primary emerald, background off-white (stone), aksen amber/oranye, radius kartu 16px.
- Halaman customer memakai `layouts/customer`; admin memakai `layouts/admin`. Route admin diawali prefix `/admin` dan hanya untuk CRUD Produk/Kategori + pengaturan WhatsApp.
- **Hero section** di beranda adalah slider yang mengikuti produk `ready` dari database (maks 10), bisa di-swipe di HP maupun drag di PC.