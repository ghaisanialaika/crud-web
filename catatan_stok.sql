-- ============================================================================
-- DATABASE SCHEMA: catatan_stok
-- ============================================================================
-- File ini dapat diimpor langsung melalui phpMyAdmin atau terminal MySQL:
-- mysql -u root -p < catatan_stok.sql

CREATE DATABASE IF NOT EXISTS `catatan_stok` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `catatan_stok`;

-- --------------------------------------------------------
-- Struktur Tabel `items` (Daftar Barang Inventaris)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Nama barang',
  `category` varchar(60) NOT NULL COMMENT 'Kategori barang',
  `stock` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Jumlah unit stok tersedia',
  `price` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Harga satuan per unit dalam Rupiah',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Waktu penambahan',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Waktu terakhir diubah',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Data Awal Sampel (Sample Records)
-- --------------------------------------------------------
INSERT INTO `items` (`id`, `name`, `category`, `stock`, `price`) VALUES
(1, 'Kopi Arabika Gayo 250g', 'Minuman', 45, 85000),
(2, 'Teh Hijau Melati Organik', 'Minuman', 28, 35000),
(3, 'Brownies Panggang Almond', 'Makanan', 15, 65000),
(4, 'Keripik Kentang Balado', 'Makanan', 80, 18000),
(5, 'Notebook Spiral Dotted A5', 'Alat Tulis', 50, 42000),
(6, 'Pulpen Gel 0.5mm Hitam', 'Alat Tulis', 120, 8000),
(7, 'Mouse Wireless Silent Click', 'Elektronik', 14, 125000),
(8, 'Kabel Fast Charging Type-C', 'Elektronik', 64, 45000),
(9, 'Webcam HD 1080p Autofocus', 'Elektronik', 8, 275000);
