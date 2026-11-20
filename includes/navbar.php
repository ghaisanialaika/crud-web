<?php
/**
 * ============================================================================
 * NAVBAR TEMPLATE (includes/navbar.php)
 * ============================================================================
 * Menampilkan bar navigasi atas kapsul memanjang:
 * - Brand: Catatan Stok dengan logo ikon Cognify
 * - Menu: Dashboard, Daftar Barang, Analisis Stok
 * - Utilitas: Avatar Profil Pengguna
 */
?>
<header class="w-full pt-4 pb-2 px-4 sm:px-8 lg:px-12">
    <!-- Kontainer Kapsul Utuh Navigasi -->
    <div class="w-full bg-white rounded-full border border-gray-200/80 p-2 sm:p-2.5 flex items-center justify-between shadow-xs">
        
        <!-- 1. SISI KIRI: Brand Pill Catatan Stok -->
        <a href="index.php" class="bg-[#F2F4F7] hover:bg-gray-200/80 rounded-full px-5 sm:px-6 py-2.5 sm:py-3 flex items-center gap-2.5 transition">
            <img src="assets/img/cognify-logo.png" alt="Logo Catatan Stok" class="h-5 sm:h-6 w-auto object-contain">
            <span class="text-base sm:text-lg font-bold tracking-tight text-gray-900">Catatan<span class="text-gray-400 font-semibold ml-0.5">Stok</span></span>
        </a>

        <!-- 2. SISI TENGAH: Tombol Pill Navigasi (Dashboard, Daftar Barang, Analisis Stok) -->
        <nav class="flex items-center gap-2 sm:gap-3 overflow-x-auto py-1">
            <!-- Dashboard (Tab Ringkasan Aktif) -->
            <a href="#dashboard" 
               class="bg-[#18191B] hover:bg-black text-white px-5 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium shadow-sm transition whitespace-nowrap">
                Dashboard
            </a>
            
            <!-- Daftar Barang -->
            <a href="#inventory-section" 
               class="bg-[#F2F4F7] hover:bg-gray-200/80 text-gray-800 px-5 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium transition whitespace-nowrap">
                Daftar Barang
            </a>
            
            <!-- Analisis Stok -->
            <a href="#overview-section" 
               class="bg-[#F2F4F7] hover:bg-gray-200/80 text-gray-800 px-5 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium transition whitespace-nowrap">
                Analisis Stok
            </a>
        </nav>

        <!-- 3. SISI KANAN: Avatar Profil Pengguna -->
        <div class="flex items-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center cursor-pointer shadow-xs border border-gray-200/50">
                <img src="https://rmol.id/images/berita/normal/2024/05/943640_09243510052024_Prabowo_Subianto_diam.jpg" alt="Foto Pengguna" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>

<!-- Main Fullscreen Content Container -->
<main class="w-full px-4 sm:px-8 lg:px-12 py-6 pb-12 flex flex-col gap-8 flex-1">
