<?php
/**
 * ============================================================================
 * KONFIGURASI DATABASE (config/database.php)
 * ============================================================================
 * File ini bertugas menghubungkan PHP dengan database MySQL menggunakan PDO.
 * PDO (PHP Data Objects) adalah cara standar dan aman di PHP modern untuk
 * berinteraksi dengan database (mencegah SQL Injection).
 * 
 * Untuk pemula:
 * - host     : Alamat server database (biasanya 'localhost' untuk XAMPP).
 * - dbname   : Nama database yang dituju ('catatan_stok').
 * - username : Pengguna MySQL (default XAMPP adalah 'root').
 * - password : Kata sandi MySQL (default XAMPP adalah string kosong '').
 */

declare(strict_types=1);

// Pengaturan kredensial database
const DB_HOST = 'localhost';
const DB_NAME = 'catatan_stok';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

/**
 * Fungsi untuk mendapatkan koneksi database PDO.
 * Mengembalikan objek PDO jika berhasil, atau null jika gagal.
 *
 * @return PDO|null
 */
function getDatabaseConnection(): ?PDO {
    // static variable agar koneksi tidak dibuat berulang kali (singleton pattern sederhana)
    static $pdoInstance = null;

    if ($pdoInstance !== null) {
        return $pdoInstance;
    }

    // DSN (Data Source Name) mendeskripsikan tipe database, host, nama db, dan charset
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);

    $options = [
        // Lempar exception saat terjadi kesalahan query agar mudah dilacak
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Kembalikan hasil query dalam bentuk array asosiatif (nama_kolom => nilai)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Nonaktifkan prepared statement emulation agar keamanan SQL Injection maksimal
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdoInstance = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdoInstance;
    } catch (PDOException $e) {
        // Catat error di server log untuk keperluan debugging
        error_log('Database Connection Error: ' . $e->getMessage());
        return null;
    }
}
