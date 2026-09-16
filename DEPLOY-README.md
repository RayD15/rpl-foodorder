# TamsisFood Deployment

## Cara Deploy
1. Upload semua isi folder ini ke hosting/lokasi deploy.
2. Pastikan folder `storage` dan `bootstrap/cache` memiliki izin `775` atau `777`.
3. **Symlink Otomatis**: Middleware `EnsureStorageLink` akan otomatis membuat symlink `public/storage` jika belum ada atau rusak.

## Database
- Aplikasi ini menggunakan SQLite (`database/database.sqlite`).
- Pastikan file database diizinkan untuk ditulis oleh server.
