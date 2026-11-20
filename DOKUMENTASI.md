# Panduan Cara Kerja Aplikasi Catatan Stok & Project Management Overview

Halo! Selamat datang di dunia web development modern. Dokumen ini dibuat khusus untuk kamu yang baru mulai belajar agar kamu benar-benar paham logika di balik layar tentang bagaimana aplikasi ini bekerja, bagaimana PHP bisa menyambung ke database, bagaimana kode modular dipisah-pisah, dan bagaimana proses CRUD serta antarmuka Tailwind CSS berjalan.

---

## 1. Memahami Struktur Kode Modular (.php)

Pada versi terbaru ini, seluruh kode tampilan dan logika telah dipisah-pisah (*modularization*) agar rapi dan tidak menumpuk di satu file raksasa.

### Mengapa kodenya dipisah-pisah?
Bayangkan kamu sedang merakit lemari atau bermain LEGO. Jauh lebih mudah jika bagian pintu, rak, baut, dan pegangan diletakkan di wadah terpisah daripada dicampur aduk dalam satu kotak besar.

Inilah pembagian file di proyek ini:

1. **`index.php` (Pintu Masuk Utama):**
   Halaman yang pertama kali dipanggil saat pengguna membuka web di browser. Tugasnya adalah memanggil modul-modul lainnya secara berurutan menggunakan perintah `require_once`.
2. **`config/database.php` (Kunci Koneksi):**
   Berisi pengaturan host, nama database, username, dan password MySQL dengan sistem keamanan **PDO (PHP Data Objects)**.
3. **`includes/header.php`:**
   Memuat bagian `<head>`, font modern *Plus Jakarta Sans*, dan stylesheet **Tailwind CSS via CDN**.
4. **`includes/navbar.php`:**
   Menyediakan bar navigasi atas berdesain kapsul (pill) khas Cognify.
5. **`includes/title_bar.php`:**
   Menampilkan judul "Project Management Overview" dan tombol "+ New task".
6. **`includes/stat_cards.php`:**
   Menampilkan 3 kartu ringkasan di baris atas (Active Projects, Total Tasks, Team Members).
7. **`includes/overview_cards.php`:**
   Menampilkan 3 widget analisis di baris bawah (Task Progress grafik bertekstur garis diagonal, Project Status dengan Donut Chart melingkar, dan Productivity Trend dengan kurva gelombang).
8. **`includes/inventory_table.php`:**
   Tabel tempat menampilkan barang, kolom aksi Ubah/Hapus, pencarian langsung (*live search*), serta tombol filter kategori.
9. **`includes/modals.php`:**
   Dialog formulir pop-up untuk menambah atau mengubah data barang serta kotak konfirmasi hapus.
10. **`includes/footer.php`:**
    Menutup tag HTML, menyediakan wadah notifikasi (*Toast*), dan memanggil file JavaScript.
11. **`api.php`:**
    Petugas backend yang melayani permintaan data dalam bentuk JSON.
12. **`assets/js/app.js`:**
    Mengatur interaksi langsung seperti mengetik di kotak pencarian, membuka modal tanpa reload halaman, dan mengirim data ke `api.php`.

---

## 2. Mengapa Menggunakan Tailwind CSS via CDN?

Sebelumnya aplikasi menggunakan CSS manual atau Bootstrap. Dengan **Tailwind CSS via CDN**:
- Kamu tidak perlu menginstal Node.js atau compiler rumit. Cukup pasang satu baris skrip `<script src="https://cdn.tailwindcss.com"></script>` di `includes/header.php`.
- Semua gaya tampilan diatur lewat *utility classes* yang jelas artinya, contoh:
  - `bg-white` : Memberi latar putih.
  - `rounded-3xl` : Membuat sudut membulat elegan sebesar 24px.
  - `p-6` : Memberi ruang jarak dalam (*padding*) sebesar 1.5rem (24px).
  - `shadow-sm` / `shadow-2xl` : Memberi bayangan lembut yang memberikan efek kedalaman (depth).

---

## 3. Bagaimana Cara PHP Terhubung ke Database?

Menghubungkan PHP ke database itu konsep dasarnya persis seperti membuka pintu ruangan yang terkunci. PHP butuh membawa empat "kunci identitas":

1. **Alamat Host:** Di mana database berada. Karena berjalan di laptop sendiri menggunakan XAMPP, alamatnya adalah `localhost`.
2. **Nama Database:** `catatan_stok`.
3. **Username:** Pengguna yang punya izin akses, standarnya adalah `root`.
4. **Password:** Kunci pengaman. Bawaan standar XAMPP adalah string kosong (`''`).

Proses ini dilakukan di file `config/database.php` menggunakan blok `try ... catch`. Jika MySQL aktif dan kuncinya cocok, koneksi berhasil dibuka. Jika MySQL lupa dinyalakan di XAMPP, PHP akan menangkap error tersebut dengan aman tanpa merusak tampilan.

---

## 4. Cara Kerja CRUD (Create, Read, Update, Delete)

CRUD adalah empat aktivitas pokok dalam setiap aplikasi pengelola data:

### A. Create (Menambah Barang Baru)
1. Pengguna mengklik tombol **"+ New task"** atau **"+ Tambah Barang"**.
2. Modal dialog formulir muncul di layar.
3. Pengguna mengisi nama, kategori, jumlah stok, dan harga, lalu klik "Simpan Barang".
4. JavaScript (`app.js`) membungkus data tersebut dan mengirimkannya ke `api.php?action=create` menggunakan metode `POST`.
5. `api.php` memeriksa apakah datanya valid, lalu menjalankan query SQL `INSERT INTO items ...`.
6. Database menyimpan baris baru dan memberikan nomor ID unik.
7. JavaScript menerima kabar sukses, menutup modal, memunculkan notifikasi Toast, dan memperbarui tabel secara otomatis.

### B. Read (Membaca dan Menampilkan Barang)
1. Saat halaman `index.php` dibuka, PHP langsung mengambil data dari tabel `items` untuk tampilan awal yang cepat.
2. JavaScript juga memanggil `api.php?action=list` untuk menghitung statistik dinamis (total stok, status aman/menipis, nilai aset).
3. Data disusun ke dalam baris-baris tabel dengan badge status stok yang ramah mata (Hijau jika aman, Kuning jika menipis, Merah jika habis).

### C. Update (Mengubah Data Barang)
1. Pengguna mengklik tombol **"Ubah"** pada salah satu baris barang.
2. JavaScript mengambil data barang yang dipilih dan memasukkannya ke formulir modal.
3. Pengguna memperbarui data yang diinginkan, misalnya menambah stok atau mengubah harga.
4. Saat formulir disubmit, JavaScript mengirim data ke `api.php?action=update&id=...` dengan metode `PUT`.
5. `api.php` menjalankan query `UPDATE items SET ... WHERE id = :id`.
6. Tabel dan grafik diperbarui dengan data yang paling segar.

### D. Delete (Menghapus Barang)
1. Pengguna mengklik tombol **"Hapus"**.
2. Kotak dialog konfirmasi muncul: *"Yakin ingin menghapus?"* untuk mencegah salah klik.
3. Saat pengguna memilih "Ya, Hapus", permintaan dikirim ke `api.php?action=delete&id=...`.
4. `api.php` mengeksekusi `DELETE FROM items WHERE id = :id`.
5. Baris barang langsung lenyap dari tabel dan notifikasi sukses muncul.

---

## 5. Ringkasan Singkat untuk Belajar

- **Modularitas:** Pecah kode besar jadi bagian-bagian kecil (`header.php`, `navbar.php`, `modals.php`, dll) agar mudah dibaca dan dirawat.
- **Tailwind CDN:** Mempermudah styling antarmuka modern langsung di class HTML tanpa konfigurasi rumit.
- **PDO Prepared Statements:** Selalu gunakan PDO dengan parameter binding (`:name`, `:category`, dll) agar aman dari serangan peretas (SQL Injection).
- **Asynchronous AJAX:** Pengguna tidak perlu memuat ulang seluruh halaman (*page refresh*) setiap kali menambah, mengedit, atau menghapus data.
