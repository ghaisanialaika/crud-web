<?php
/**
 * ============================================================================
 * HEADER TEMPLATE (includes/header.php)
 * ============================================================================
 * Bagian <head> halaman web: memuat meta tag, Google Font (Plus Jakarta Sans),
 * Tailwind CSS CDN, serta konfigurasi warna & gaya visual pendukung.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Stok — Ringkasan & Manajemen Inventaris</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Konfigurasi Tema Tailwind CSS -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#18191B',      // Hitam charcoal untuk tombol utama & tab aktif
                            green: '#10B981',     // Hijau cerah untuk status aman
                            blue: '#2563EB',      // Biru untuk total unit
                            pink: '#EC4899',      // Pink magenta untuk kategori & status menipis
                            lightGray: '#F3F4F6', // Latar belakang tombol netral
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03)',
                        'modal': '0 20px 40px -15px rgba(0, 0, 0, 0.15)',
                    }
                }
            }
        };
    </script>

    <!-- Custom CSS untuk pola garis diagonal & styling dialog -->
    <style>
        .pattern-stripes-green {
            background-color: #10B981;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.25) 0,
                rgba(255, 255, 255, 0.25) 8px,
                transparent 8px,
                transparent 16px
            );
        }
        .pattern-stripes-pink {
            background-color: #EC4899;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.25) 0,
                rgba(255, 255, 255, 0.25) 8px,
                transparent 8px,
                transparent 16px
            );
        }
        .pattern-stripes-blue {
            background-color: #3B82F6;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.25) 0,
                rgba(255, 255, 255, 0.25) 8px,
                transparent 8px,
                transparent 16px
            );
        }

        dialog::backdrop {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
        }

        .smooth-transition {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-[#F8F9FA] min-h-screen text-gray-900 font-sans antialiased flex flex-col">
