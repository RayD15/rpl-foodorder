Siap. Berikut **PRD FINAL RPL2 FoodOrder** yang sudah menyesuaikan semua keputusan: **mobile-first, pemesanan makanan/minuman, checkout via WhatsApp, tanpa sistem status pesanan, dan admin berkomunikasi langsung lewat WhatsApp.**

# PRD — RPL2 FoodOrder

**Version:** 1.0
**Project:** RPL2 FoodOrder
**Type:** Web Application — Food & Beverage Ordering
**Target:** Siswa/Guru Sekolah
**Platform:** Mobile Web
**Purpose:** Tugas KDI — Rekayasa Perangkat Lunak

---

## 1. Product Overview

**RPL2 FoodOrder** adalah website pemesanan makanan dan minuman yang memungkinkan pengguna melihat menu, memilih produk, memasukkannya ke keranjang, mengisi data pemesan, kemudian mengirim detail pesanan langsung ke **WhatsApp Admin**.

Website tidak menggunakan sistem pembayaran online maupun sistem status pesanan. Setelah pesanan dikirim ke WhatsApp, **Admin dan pembeli melanjutkan komunikasi secara langsung melalui WhatsApp**.

### Konsep Utama

> **Pilih → Keranjang → Checkout → WhatsApp Admin**

---

# 2. Problem Statement

Proses pemesanan makanan/minuman di lingkungan sekolah sering dilakukan secara manual melalui chat. Hal ini dapat menyebabkan:

* Pembeli harus bertanya mengenai menu satu per satu.
* Informasi harga kurang terstruktur.
* Pesanan berpotensi salah dicatat.
* Pembeli harus menghitung total pesanan sendiri.
* Format pesan setiap pembeli tidak konsisten.

**RPL2 FoodOrder** menyelesaikan masalah tersebut dengan menyediakan katalog digital dan membuat pesan WhatsApp otomatis berdasarkan keranjang pengguna.

---

# 3. Goals

### Primary Goals

* Memudahkan siswa/guru melihat menu.
* Memudahkan pengguna memilih makanan dan minuman.
* Menghitung total pesanan secara otomatis.
* Menghasilkan format pesanan yang rapi.
* Mengirim pesanan langsung ke WhatsApp Admin.
* Mengutamakan pengalaman pengguna **mobile-first**.

### Non-Goals

Versi 1.0 **tidak mencakup:**

* ❌ Payment gateway
* ❌ Pembayaran otomatis
* ❌ Tracking/status pesanan
* ❌ Riwayat pesanan customer
* ❌ Chat system di dalam website
* ❌ Login customer
* ❌ Sistem delivery
* ❌ Notifikasi otomatis

---

# 4. Target User

### Customer

Siswa atau guru yang ingin membeli makanan/minuman.

Contoh:

> Siswa X RPL 2 ingin membeli 2 Churros dan 1 Matcha.

### Admin

Pengelola makanan/minuman yang menerima pesanan melalui WhatsApp.

Admin **tidak perlu dashboard pesanan kompleks**. Setelah customer mengirim pesanan, admin langsung berkomunikasi dengan customer melalui WhatsApp.

---

# 5. User Flow

```text
                    ┌───────────┐
                    │   HOME    │
                    └─────┬─────┘
                          ↓
                    ┌───────────┐
                    │   MENU    │
                    └─────┬─────┘
                          ↓
                    ┌───────────┐
                    │ PRODUK    │
                    └─────┬─────┘
                          ↓
                    ┌───────────┐
                    │ KERANJANG │
                    └─────┬─────┘
                          ↓
                    ┌───────────┐
                    │ CHECKOUT  │
                    └─────┬─────┘
                          ↓
                  ┌─────────────────┐
                  │ WHATSAPP ADMIN  │
                  └────────┬────────┘
                           ↓
                  ┌─────────────────┐
                  │ CHAT LANGSUNG   │
                  │ DENGAN ADMIN    │
                  └─────────────────┘
```

---

# 6. Struktur Halaman

## 6.1 Home

Tujuan: memberikan pengenalan singkat dan membawa pengguna ke menu.

### Components

* Header RPL2 FoodOrder
* Hero section (slider produk ready)
* Search bar
* Kategori
* CTA "Lihat Menu"
* Floating cart

Contoh:

```text
RPL2 FoodOrder

"Mau pesan apa hari ini?"

[ 🔍 Cari makanan atau minuman ]

Kategori
[ Semua ] [ Makanan ] [ Minuman ]

        🛒 3 Item • Rp32.000
```

---

# 7. Menu Page

Menampilkan seluruh produk.

### Features

* Search
* Filter kategori
* Product grid
* Harga
* Gambar
* Tombol tambah
* Jumlah produk

### Mobile Layout

```text
┌──────────────────────┐
│ ← Menu               │
│ 🔍 Cari produk...    │
│                      │
│ [Semua] [Makanan]    │
│ [Minuman]            │
│                      │
│ ┌──────┐  ┌──────┐   │
│ │ FOTO │  │ FOTO │   │
│ │      │  │      │   │
│ │Churros│ │Matcha│   │
│ │Rp5.000│ │Rp12K │   │
│ │  [+] │  │ [+]  │   │
│ └──────┘  └──────┘   │
└──────────────────────┘
```

---

# 8. Product Detail

Ketika pengguna memilih produk.

### Information

* Foto produk
* Nama
* Harga
* Deskripsi
* Kategori
* Jumlah
* Tombol "Tambah ke Keranjang"

Contoh:

```text
Churros

Rp5.000 / 3 pcs

Churros crispy dengan rasa
manis dan lezat.

Jumlah
[ − ]  2  [ + ]

[ Tambah ke Keranjang ]
```

---

# 9. Shopping Cart

Menampilkan produk yang dipilih.

### Features

* Produk
* Harga
* Quantity
* Increase/decrease quantity
* Remove product
* Subtotal
* Total

Contoh:

```text
Keranjang

Churros
Rp5.000
[ − ] 2 [ + ]

Matcha
Rp12.000
[ − ] 1 [ + ]

────────────────

Total
Rp22.000

[ Lanjut Checkout ]
```

---

# 10. Checkout

Checkout dibuat **sangat sederhana**.

### Required Fields

* Nama
* Kelas

### Optional

* Catatan

### Order Summary

```text
Checkout

Informasi Pemesan

Nama
[________________]

Kelas
[________________]

Catatan
[________________]

Pesanan
────────────────
Churros × 2     Rp10.000
Matcha × 1      Rp12.000

Total           Rp22.000

┌───────────────────────┐
│ 🟢 PESAN VIA WHATSAPP │
└───────────────────────┘
```

---

# 11. WhatsApp Ordering System

Ini adalah **fitur utama aplikasi**.

Ketika pengguna menekan:

> **Pesan via WhatsApp**

Website membuat pesan otomatis berdasarkan:

* Nama
* Kelas
* Produk
* Jumlah
* Harga
* Total
* Catatan

Kemudian website membuka WhatsApp Admin.

### Template Message

```text
Halo Admin RPL2 FoodOrder 👋

Saya ingin memesan:

🍩 Churros × 2 = Rp10.000
🍵 Matcha × 1 = Rp12.000

💰 Total: Rp22.000

👤 Nama: [Nama]
🏫 Kelas: [Kelas]

📝 Catatan:
[Catatan]

Mohon dikonfirmasi pesanannya.
Terima kasih 🙏
```

**Catatan:** nomor WhatsApp Admin disimpan di konfigurasi aplikasi dan dapat diganti oleh developer/admin.

---

# 12. Setelah WhatsApp Dibuka

Tidak ada halaman tracking.

Flow berhenti di:

```text
Website
   ↓
WhatsApp
   ↓
Admin menerima pesan
   ↓
Admin ↔ Customer
```

Admin melakukan konfirmasi langsung melalui WhatsApp.

---

# 13. Mobile-First Design

Ini merupakan **prioritas utama** project.

### Breakpoints

```text
Mobile   → Primary
Tablet   → Responsive
Desktop  → Responsive
```

### Mobile UX Rules

* Bottom navigation bila diperlukan.
* Floating cart.
* Tombol minimal nyaman disentuh.
* Font mudah dibaca.
* Card produk 2 kolom.
* Search mudah dijangkau.
* Checkout menggunakan layout satu kolom.
* CTA WhatsApp dibuat besar.
* Hindari terlalu banyak teks.
* Gunakan sticky/fixed cart ketika ada item.

---

# 14. Design System

### Color Direction

Gunakan warna yang fresh dan cocok dengan makanan.

**Primary:** Emerald/Green
**Background:** Off-white
**Text:** Dark charcoal
**Accent:** Orange/Yellow untuk highlight makanan

### UI Style

* Modern
* Clean
* Minimal
* Rounded
* Soft shadow
* Large product imagery
* Spacious layout

### Border Radius

```text
Cards       → 16px
Buttons     → 12px
Inputs      → 12px
Images      → 14–16px
```

---

# 15. Dark Mode

Website menyediakan tombol:

```text
☀️ / 🌙
```

Dark mode mengubah:

* Background
* Card
* Text
* Input
* Navigation

Preferensi dapat disimpan menggunakan `localStorage`.

---

# 16. Database

Untuk versi yang menggunakan PHP/MySQL atau Laravel:

### `products`

| Field       | Type      |
| ----------- | --------- |
| id          | BIGINT    |
| name        | VARCHAR   |
| category    | VARCHAR   |
| description | TEXT      |
| price       | DECIMAL   |
| image       | VARCHAR   |
| status      | VARCHAR   | (ready / sold_out)
| created_at  | TIMESTAMP |
| updated_at  | TIMESTAMP |

### `categories`

| Field      | Type      |
| ---------- | --------- |
| id         | BIGINT    |
| name       | VARCHAR   |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### `admins`

| Field      | Type      |
| ---------- | --------- |
| id         | BIGINT    |
| name       | VARCHAR   |
| email      | VARCHAR   |
| password   | VARCHAR   |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

> **Tidak perlu tabel orders/order_items** untuk MVP jika pesanan hanya dikirim ke WhatsApp dan tidak disimpan di server.

---

# 17. Admin

Karena tidak menggunakan sistem status pesanan, admin dibuat sederhana.

### Admin Panel

```text
Dashboard
│
├── Produk
│   ├── Lihat Produk
│   ├── Tambah Produk
│   ├── Edit Produk
│   └── Hapus Produk
│
├── Kategori
│   ├── Tambah
│   ├── Edit
│   └── Hapus
│
└── Pengaturan
    └── Nomor WhatsApp
```

Admin bertugas:

* Mengelola produk.
* Mengelola harga.
* Mengelola kategori.
* Mengubah gambar produk.
* Mengatur nomor WhatsApp tujuan.

**Pesanan tidak dikelola di dashboard.**

---

# 18. Tech Stack

Untuk project RPL, rekomendasi:

### Option A — Lebih sederhana

```text
Frontend
HTML
CSS
JavaScript

Backend
PHP

Database
MySQL

Server
XAMPP
```

### Option B — Lebih profesional

```text
Frontend
Blade
Tailwind CSS
JavaScript

Backend
Laravel

Database
MySQL
```

**Rekomendasi:** Laravel + MySQL jika ingin project terlihat lebih profesional dan sekalian memperkuat skill RPL.

---

# 19. Struktur Project Laravel

```text
rpl-foodorder/
│
├── app/
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   └── Admin.php
│   │
│   └── Http/
│       └── Controllers/
│           ├── HomeController.php
│           ├── ProductController.php
│           └── AdminController.php
│
├── database/
│   └── migrations/
│       ├── create_products_table.php
│       ├── create_categories_table.php
│       └── create_admins_table.php
│
├── resources/
│   └── views/
│       ├── home.blade.php
│       ├── menu.blade.php
│       ├── product.blade.php
│       ├── cart.blade.php
│       ├── checkout.blade.php
│       │
│       └── admin/
│           ├── dashboard.blade.php
│           ├── products.blade.php
│           └── categories.blade.php
│
├── public/
│   ├── images/
│   ├── css/
│   └── js/
│
└── routes/
    └── web.php
```

---

# 20. Functional Requirements

### FR-01 — Browse Products

User dapat melihat seluruh produk.

### FR-02 — Search

User dapat mencari produk berdasarkan nama.

### FR-03 — Category Filter

User dapat memfilter:

* Semua
* Makanan
* Minuman

### FR-04 — Product Detail

User dapat melihat detail produk.

### FR-05 — Add to Cart

User dapat menambahkan produk.

### FR-06 — Update Cart

User dapat mengubah jumlah produk.

### FR-07 — Remove Cart

User dapat menghapus produk.

### FR-08 — Automatic Calculation

Sistem menghitung subtotal dan total otomatis.

### FR-09 — Checkout

User dapat memasukkan nama dan kelas.

### FR-10 — WhatsApp

Sistem membuat pesan WhatsApp otomatis.

### FR-11 — Admin Product Management

Admin dapat CRUD produk.

### FR-12 — Admin Category Management

Admin dapat CRUD kategori.

### FR-13 — WhatsApp Configuration

Admin dapat mengatur nomor WhatsApp tujuan.

---

# 21. Non-Functional Requirements

### Performance

* Website ringan.
* Gambar dikompres.
* JavaScript seminimal mungkin.
* Loading cepat di jaringan sekolah/mobile.

### Responsive

Harus bekerja pada:

* Smartphone
* Tablet
* Laptop
* Desktop

### Security

* Password admin menggunakan hashing.
* Validasi input.
* CSRF protection.
* Authentication untuk admin.
* Validasi upload gambar.

### Usability

User baru harus dapat melakukan pemesanan tanpa tutorial.

---

# 22. MVP

Versi pertama cukup memiliki:

```text
✅ Home
✅ Menu
✅ Search
✅ Category
✅ Product Detail
✅ Cart
✅ Checkout
✅ Automatic Total
✅ WhatsApp Order
✅ Admin Login
✅ CRUD Product
✅ CRUD Category
✅ WhatsApp Number Setting
✅ Responsive Mobile
✅ Dark Mode
```

---

# 23. Future Development

Jika project ingin dikembangkan lebih lanjut:

```text
🔮 Payment Gateway
🔮 Order History
🔮 Order Status
🔮 QR Code Ordering
🔮 Table Number
🔮 Promo / Discount
🔮 Product Rating
🔮 Sales Analytics
🔮 Notification
🔮 PWA
```

Fitur-fitur tersebut **tidak diperlukan untuk versi tugas awal**.

---

# 24. Success Criteria

Project dianggap berhasil apabila:

* [ ] User dapat membuka website melalui HP.
* [ ] User dapat melihat menu.
* [ ] User dapat mencari produk.
* [ ] User dapat memilih kategori.
* [ ] User dapat menambahkan produk ke keranjang.
* [ ] Quantity dapat diubah.
* [ ] Total dihitung otomatis.
* [ ] User dapat mengisi nama dan kelas.
* [ ] Tombol WhatsApp menghasilkan pesan yang benar.
* [ ] WhatsApp Admin terbuka dengan pesan pesanan.
* [ ] Admin dapat mengelola produk.
* [ ] Website responsive.
* [ ] Tidak ada sistem status pesanan.
* [ ] Komunikasi pesanan dilakukan langsung melalui WhatsApp.

---

# 25. Final Product Concept

```text
                 RPL2 FOODORDER
                       │
                       ▼
              ┌─────────────────┐
              │     MOBILE      │
              │      USER       │
              └────────┬────────┘
                       │
              Browse makanan
              & minuman
                       │
                       ▼
                 🛒 KERANJANG
                       │
                       ▼
                  CHECKOUT
                       │
                Nama + Kelas
                       │
                       ▼
             🟢 WHATSAPP ADMIN
                       │
                       ▼
               ┌──────────────┐
               │    ADMIN     │
               │   WHATSAPP   │
               └──────┬───────┘
                      │
                 Chat langsung
                 dengan customer
```

## 🎯 Inti RPL2 FoodOrder

**RPL2 FoodOrder bukan marketplace besar dan bukan sistem pembayaran online.**

Fokusnya adalah:

> **Website menu + shopping cart + checkout sederhana + pemesanan langsung melalui WhatsApp.**

Dengan begitu project tetap **realistis untuk tugas KDI**, tetapi tampilannya bisa dibuat seperti aplikasi food-ordering modern, terutama di **mobile**.
