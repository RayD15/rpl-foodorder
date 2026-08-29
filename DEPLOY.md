# Panduan Deploy — RPL2 FoodOrder (Gratis)

Aplikasi Laravel + Blade + Tailwind, database **MySQL gratis (TiDB Cloud)**, di-host **Render.com (free tier)**.

> Cocok untuk tugas. Semua layanan dipakai dalam paket gratis.

---

## 1. Buat Database MySQL Gratis — TiDB Cloud

1. Buka <https://tidbcloud.com> → sign up (fork GitHub/Google).
2. **Create Cluster** → pilih **Serverless Tier** (gratis, auto-pause).
   - Region: pilih terdekat (mis. `ap-southeast-1`, Singapore).
3. Setelah jadi, klik **Connect** → **Connect with a client**.
   - Catat **Host**, **Port** (biasanya `4000`), dan **User** (mis. `xxxx.root`).
4. Buat **Password** untuk user tersebut (klik "Create password").
5. Buka tab **Chat2Query / SQL Editor**, jalankan satu perintah untuk membuat database:
   ```sql
   CREATE DATABASE IF NOT EXISTS rpl_foodorder;
   ```

**Hasil yang didapat (contoh):**
```
HOST      = gateway01.ap-southeast-1.prod.aws.tidbcloud.com
PORT      = 4000
DATABASE  = rpl_foodorder
USER      = abc123.root
PASSWORD  = password-anda
```

---

## 2. Siapkan `.env` Production

Di server, `.env` diisi dengan nilai MySQL di atas. Gunakan contoh:

```env
APP_NAME="RPL2 FoodOrder"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rpl-foodorder.onrender.com

DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=rpl_foodorder
DB_USERNAME=abc123.root
DB_PASSWORD=password-anda

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

> File contoh lengkap sudah disediakan: **`.env.production.example`**.

---

## 3. Upload Project ke GitHub

```bash
git init
git add .
git commit -m "init"
git remote add origin https://github.com/USERNAMA/rpl-foodorder.git
git push -u origin main
```

Biasanya perlu membuat repo dulu di <https://github.com/new>.

---

## 4. Deploy ke Render.com (free)

**Cara A — Blueprint (otomatis dari `render.yaml`)**
1. Buka <https://dashboard.render.com/blueprints>.
2. **New +** → **Blueprint** → hubungkan repo GitHub.
3. Render membaca `render.yaml` yang sudah disediakan di repo.
4. Setelah service terbuat, isi **Environment Variables** yang bertanda `sync: false` (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) dengan data dari langkah 1.
5. Klik **Apply / Deploy**.

**Cara B — Manual**
1. Dashboard Render → **New +** → **Web Service**.
2. Pilih repo, *runtime* **PHP**, *Setuju* default.
3. **Build Command**:
   ```bash
   composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan key:generate && php artisan migrate --force --seed && php artisan storage:link
   ```
4. **Start Command**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```
5. Di **Environment Variables**, isi semua nilai dari `.env.production.example`.
6. **Create Web Service** → deploy akan jalan otomatis.

> Free tier Render: service akan *spin down* saat tidak aktif ±15 menit, lalu menyala lagi otomatis saat diakses.

---

## 5. Setelah Deploy

1. Buka `https://NAMA-app.onrender.com`.
2. Login admin: `admin1@kdi.com` / `rplkdi1510` (dibuat otomatis oleh `--seed`).
3. Ubah **nomor WhatsApp** di menu **Pengaturan**.

---

## Catatan penting

### Upload gambar produk (storage)
- Karena Render free-tier memakai filesystem sementara, upload gambar lewat admin bisa hilang saat restart **kecuali** disimpan ke **Persistent Disk**.
- `render.yaml` sudah menyertakan disk `storage` (1GB) yang di-mount ke `/var/data`.
- Untuk memanfaatkannya, arahkan storage ke sana: di `.env` production tambahkan:
  ```env
  # agar upload tersimpan di persistent disk (tidak hilang)
  ```

### Alternatif paling anti-ribet untuk tugas
Kalau tidak ingin urusan upload & MySQL, **cara termudah tetap SQLite + Persistent Disk**:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/data/database.sqlite
```
- SQLite lalu taruh di persistent disk `/var/data` agar tidak hilang saat restart.
- Data admin & produk dibuat sekali lewat `migrate --seed`.

### Auto-pause TiDB
- TiDB Serverless **auto-pause** saat idle, jadi saat pertama akses mungkin perlu ±10 detik untuk "wake up". Wajar.

---

## Verify lokal (sebelum deploy)
- `php artisan key:generate` → `php artisan migrate --seed` → `npm run build` → `php artisan serve`
- URL: http://localhost:8000
- Jalankan test: `php artisan test`
