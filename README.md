# Catatan Stok

CRUD inventaris sederhana dengan HTML, CSS, JavaScript, PHP, dan MySQL. Database dikelola lewat phpMyAdmin.

## Menjalankan di XAMPP

1. Salin folder proyek ke `C:\xampp\htdocs\catatan-stok`.
2. Buka XAMPP Control Panel, lalu jalankan **Apache** dan **MySQL**.
3. Buka `http://localhost/phpmyadmin`, pilih **Import**, lalu impor file `catatan_stok.sql`.
4. Buka `http://localhost/catatan-stok/`.

API memakai akun MySQL bawaan XAMPP (`root` tanpa kata sandi). Jika akun lokalmu berbeda, sesuaikan koneksi PDO di bagian atas `api.php`.

Fitur: tambah, lihat, cari, ubah, dan hapus barang. Isinya mencakup nama, kategori, stok, serta harga per unit.
