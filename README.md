# Cognify — Catatan Stok & Project Management Overview

Aplikasi manajemen inventaris & CRUD stok barang modern dengan tampilan antarmuka (UI) mewah terinspirasi oleh desain Cognify Dashboard. Dibangun dengan **PHP (100% modular)**, **Tailwind CSS (via CDN)**, **JavaScript modern**, dan **MySQL**.

---

## 🎨 Tampilan & Fitur Desain
- **Desain Modern Cognify:** Sudut membulat besar (*rounded-3xl*), latar belakang bersih bertekstur titik halus (*dot canvas*), tombol pill hitam arang (*#18191B*), serta aksen warna Emerald, Blue, dan Pink yang konsisten.
- **Top Navigation Bar:** Logo Cognify, navigasi pill kapsul, ikon utilitas, dan avatar profil.
- **3 Stat Cards Utama:**
  - *Active Projects* (Ikon hijau, metrik utama, tren perubahan).
  - *Total Tasks* (Ikon biru, metrik total unit stok, tren perubahan).
  - *Team Members* (Ikon pink, kategori aktif, avatar stack pelanggan).
- **3 Overview & Analytics Cards:**
  - *Task Progress* (Grafik vertikal berpola garis diagonal / *striped pattern*).
  - *Project Status* (Donut Chart SVG 3 warna melingkar, indikator tengah, & status breakdown bar).
  - *Productivity Trend* (Grafik kurva gelombang multi-line interaktif hari kerja & waktu aktif).
- **Tabel CRUD Inventaris Lengkap:**
  - Pencarian langsung (*Live Search*) berdasarkan nama dan kategori.
  - Filter kategori cepat via tombol pill (Semua, Makanan, Minuman, Alat Tulis, Elektronik).
  - Dialog pop-up (*native HTML5 dialog*) untuk Tambah Barang & Ubah Barang.
  - Dialog konfirmasi sebelum menghapus barang dengan penanda aman.
  - *Toast Notification* mengambang untuk umpan balik instan setiap aksi.

---

## 📁 Struktur Kode Modular (Ramah Pemula)

Semua kode program telah dipisah-pisah berdasarkan fungsinya agar sangat mudah dipelajari oleh pemula:

```
crud-web/
├── index.php                      # Pintu masuk utama (menggabungkan seluruh modul tampilan)
├── api.php                        # Endpoint RESTful JSON API untuk Create, Read, Update, Delete
├── catatan_stok.sql               # File skrip SQL database & data sampel
├── config/
│   └── database.php               # Konfigurasi koneksi MySQL dengan PDO & try-catch
├── includes/
│   ├── header.php                 # Tag <head>, Plus Jakarta Sans, Tailwind CDN & custom styles
│   ├── navbar.php                 # Bar navigasi atas (Logo, tab menu pill, ikon profil)
│   ├── title_bar.php              # Judul "Project Management Overview" & tombol "+ New task"
│   ├── stat_cards.php             # 3 Kartu statistik baris atas
│   ├── overview_cards.php         # 3 Kartu grafik analisis baris bawah
│   ├── inventory_table.php        # Tabel CRUD data barang, live search, & filter kategori
│   ├── modals.php                 # Modal dialog Tambah/Ubah Barang & Konfirmasi Hapus
│   └── footer.php                 # Penutup kartu, toast notifikasi, & pemanggilan JavaScript
├── assets/
│   └── js/
│       └── app.js                 # Logika JavaScript interaktif (AJAX fetch, render, filter)
└── DOKUMENTASI.md                 # Penjelasan konsep CRUD & arsitektur kode untuk pemula
```

---

## 🚀 Cara Menjalankan di XAMPP

1. **Salin folder proyek** ke dalam direktori `htdocs` XAMPP:
   - Windows: `C:\xampp\htdocs\crud-web`
   - Linux: `/opt/lampp/htdocs/crud-web`
2. Buka **XAMPP Control Panel**, lalu aktifkan modul **Apache** dan **MySQL**.
3. Buka browser dan kunjungi `http://localhost/phpmyadmin`.
4. Buat database baru bernama `catatan_stok` atau pilih menu **Import**, lalu pilih file `catatan_stok.sql` yang ada di dalam folder proyek ini.
5. Akses aplikasi melalui browser di alamat:
   ```
   http://localhost/crud-web/
   ```

---

## 🛠️ Konfigurasi Database
Jika username atau password MySQL kamu berbeda dari setelan bawaan XAMPP (`root` tanpa password), kamu bisa mengubahnya dengan mudah di file:
👉 `config/database.php`
