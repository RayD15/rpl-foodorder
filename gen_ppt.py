from fpdf import FPDF

class SlidePDF(FPDF):
    def slide(self, num, title, bullets):
        self.add_page()
        # Title bar
        self.set_fill_color(16, 185, 129)  # emerald
        self.rect(0, 0, 210, 30, 'F')
        self.set_font('Arial', 'B', 22)
        self.set_text_color(255, 255, 255)
        self.set_xy(15, 6)
        self.cell(0, 18, f'{num}. {title}', 0, 1)
        # Body
        self.set_text_color(0, 0, 0)
        self.set_font('Arial', '', 13)
        self.set_xy(15, 38)
        for b in bullets:
            self.set_x(20)
            self.cell(5, 9, '-', 0, 0)
            self.multi_cell(0, 9, f'  {b}')
            self.ln(1)

pdf = SlidePDF()
pdf.set_auto_page_break(auto=True, margin=15)

# Cover
pdf.add_page()
pdf.set_fill_color(16, 185, 129)
pdf.rect(0, 0, 210, 297, 'F')
pdf.set_font('Arial', 'B', 36)
pdf.set_text_color(255, 255, 255)
pdf.set_xy(0, 80)
pdf.cell(0, 15, 'Pesen Dong!', 0, 1, 'C')
pdf.set_font('Arial', '', 18)
pdf.set_xy(0, 105)
pdf.cell(0, 10, 'Sistem Pemesanan Makanan Mobile-First', 0, 1, 'C')
pdf.set_xy(0, 125)
pdf.set_font('Arial', '', 14)
pdf.cell(0, 8, 'Tugas KDI RPL', 0, 1, 'C')
pdf.cell(0, 8, 'Laravel 12 + Tailwind CSS v4 + SQLite + Vite', 0, 1, 'C')
pdf.set_xy(0, 170)
pdf.set_font('Arial', '', 12)
pdf.cell(0, 8, 'Teknologi RPL | 2026', 0, 1, 'C')

# Slide 1
pdf.slide(1, 'Latar Belakang & Tujuan', [
    'Problem: Pemesanan manual lambat, antrean menumpuk, pencatatan rentan salah.',
    'Solusi: Aplikasi web mobile-first, pesan langsung dari browser HP.',
    'Tanpa login pembeli: Siapapun bisa pesan tanpa daftar akun.',
    'Tanpa database order: Pesanan dikirim langsung ke WhatsApp admin.',
    'Target: Kantin sekolah/kampus atau UMKM kuliner skala kecil.',
])

# Slide 2
pdf.slide(2, 'Fitur Utama', [
    'Katalog Menu: Tampilan grid responsif (2 kolom di mobile).',
    'Filter Kategori: Memisahkan makanan dan minuman.',
    'Sistem Bundle: Paket hemat gabungan beberapa produk.',
    'Smart Keranjang: Disimpan di browser (LocalStorage), tidak membebani server.',
    'Validasi Otomatis: Produk sold out dihapus dari keranjang otomatis.',
    'Checkout WhatsApp: Isi nama & kelas, konfirmasi, langsung buka chat WA admin.',
])

# Slide 3
pdf.slide(3, 'Arsitektur & Teknologi', [
    'Backend : Laravel 12 (PHP 8.2+).',
    'Database: SQLite (file, zero-config, tanpa server SQL).',
    'Frontend: Tailwind CSS v4 + Vite (build cepat, kompatibel modern).',
    'Icons   : Lucide Icons.',
    'Deployment: Docker (Apache) & Shared Hosting (cPanel/InfinityFree).',
    'Dark Mode: Tersimpan di browser, support light/dark.',
])

# Slide 4
pdf.slide(4, 'Panel Admin (Dashboard)', [
    'Dashboard: Ringkasan jumlah produk & kategori.',
    'CRUD Produk: Tambah, edit, hapus, upload gambar, atur status (Ready/Sold Out).',
    'CRUD Kategori: Kelompokkan menu (Makanan/Minuman).',
    'CRUD Bundle: Buat & kelola paket promo.',
    'Setting WhatsApp: Ubah nomor tujuan pesanan kapan saja.',
    'Autentikasi: Login admin aman (password ter-hash).',
])

# Slide 5
pdf.slide(5, 'Alur Kerja Aplikasi', [
    '1. Buka halaman utama -> Lihat katalog menu.',
    '2. Pilih produk -> Klik "Tambah ke Keranjang".',
    '3. Buka keranjang -> Cek item & total harga.',
    '4. Klik "Checkout" -> Isi nama & kelas.',
    '5. Konfirmasi pesanan di modal.',
    '6. Otomatis buka WhatsApp admin dengan teks pesanan terformat.',
])

# Slide 6
pdf.slide(6, 'Database & Struktur Data', [
    'Tabel products: id, name, price, image, category_id, status, description.',
    'Tabel categories: id, name, description.',
    'Tabel bundles & bundle_items: Paket hemat gabungan produk.',
    'Tabel admins: id, name, email, password (hashed).',
    'Tabel settings: Key-value (whatsapp_number, dll).',
    'Tanpa tabel orders/order_items: Semua pesanan via WhatsApp.',
])

# Slide 7
pdf.slide(7, 'Kesimpulan & Terima Kasih', [
    'Pesen Dong! efisien, cepat, ringan, & tanpa biaya server mahal.',
    'Cocok untuk kantin, UMKM, atau projek RPL pemula.',
    'Open source & mudah dikembangkan lebih lanjut.',
    'Terima kasih! Siap untuk tanya jawab.',
])

path = "C:/FILE Rayhand Ayandrie/WEB KDI/Presentasi_PesenDong.pdf"
pdf.output(path)
print(f"PDF berhasil: {path}")
