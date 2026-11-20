<?php
/**
 * ============================================================================
 * STAT CARDS TEMPLATE (includes/stat_cards.php)
 * ============================================================================
 * Menampilkan 3 kartu ringkasan baris atas yang disesuaikan dengan konteks Catatan Stok:
 * 1. Total Jenis Barang (Aksen Hijau - jumlah macam barang tercatat)
 * 2. Total Unit Stok   (Aksen Biru  - total seluruh unit fisik yang ada)
 * 3. Total Kategori    (Aksen Pink  - ragam pengelompokan produk)
 */

$totalItemsCount = $summary['total_items'] ?? 0;
$totalStockCount = $summary['total_stock'] ?? 0;
$totalCategories = $summary['total_categories'] ?? 0;
?>
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- -----------------------------------------------------------------------
         KARTU 1: TOTAL JENIS BARANG (Aksen Hijau)
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Baris Atas: Ikon Hijau + Label + Tombol Panah (↗) -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-brand-green flex items-center justify-center text-white shadow-sm ring-4 ring-emerald-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="16" rx="3"/>
                            <path d="M7 8h10"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Jenis</span>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Barang</span>
                    </div>
                </div>

                <a href="#inventory-section" aria-label="Lihat Semua Barang" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:border-gray-400 hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17"/>
                    </svg>
                </a>
            </div>

            <!-- Nilai Utama & Tren -->
            <div class="mt-5 flex items-baseline gap-2">
                <span id="stat-active-projects" class="text-4xl sm:text-[42px] font-extrabold text-gray-900 tracking-tight">
                    <?= htmlspecialchars((string)$totalItemsCount) ?>
                </span>
                <span class="text-xs font-semibold text-rose-500 flex items-center gap-0.5">
                    ↓ 12%
                </span>
            </div>
        </div>

        <!-- Baris Bawah: Catatan & Badge -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
            <div class="flex items-center gap-1.5 text-gray-400 font-medium">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.178 8c5.096 0 5.096 8 0 8-3.072 0-4.636-2.545-6.178-4-1.542-1.455-3.106-4-6.178-4-5.096 0-5.096 8 0 8 3.072 0 4.636-2.545 6.178-4 1.542-1.455 3.106-4 6.178-4z"/>
                </svg>
                <span>Dynamic of changes</span>
            </div>
            <span class="px-3 py-1 bg-gray-100/90 text-gray-600 rounded-full font-medium text-[11px]">
                Monthly
            </span>
        </div>
    </div>

    <!-- -----------------------------------------------------------------------
         KARTU 2: TOTAL UNIT STOK (Aksen Biru)
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Baris Atas: Ikon Biru + Label + Tombol Panah (↗) -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-brand-blue flex items-center justify-center text-white shadow-sm ring-4 ring-blue-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Total</span>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Unit Stok</span>
                    </div>
                </div>

                <a href="#inventory-section" aria-label="Lihat Rincian Stok" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:border-gray-400 hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17"/>
                    </svg>
                </a>
            </div>

            <!-- Nilai Utama & Tren -->
            <div class="mt-5 flex items-baseline gap-2">
                <span id="stat-total-tasks" class="text-4xl sm:text-[42px] font-extrabold text-gray-900 tracking-tight">
                    <?= number_format($totalStockCount, 0, ',', '.') ?>
                </span>
                <span class="text-xs font-semibold text-emerald-600 flex items-center gap-0.5">
                    ↑ 23%
                </span>
            </div>
        </div>

        <!-- Baris Bawah: Catatan & Badge -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
            <div class="flex items-center gap-1.5 text-gray-400 font-medium">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.178 8c5.096 0 5.096 8 0 8-3.072 0-4.636-2.545-6.178-4-1.542-1.455-3.106-4-6.178-4-5.096 0-5.096 8 0 8 3.072 0 4.636-2.545 6.178-4 1.542-1.455 3.106-4 6.178-4z"/>
                </svg>
                <span>Tersedia di gudang</span>
            </div>
            <span class="px-3 py-1 bg-gray-100/90 text-gray-600 rounded-full font-medium text-[11px]">
                Monthly
            </span>
        </div>
    </div>

    <!-- -----------------------------------------------------------------------
         KARTU 3: TOTAL KATEGORI & PENGELOMPOKAN (Aksen Pink)
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Baris Atas: Ikon Pink + Label + Tombol Panah (↗) -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-brand-pink flex items-center justify-center text-white shadow-sm ring-4 ring-pink-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Total</span>
                        <span class="block text-sm font-semibold text-gray-800 leading-tight">Kategori</span>
                    </div>
                </div>

                <a href="#inventory-section" aria-label="Lihat Kategori" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:border-gray-400 hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7V17"/>
                    </svg>
                </a>
            </div>

            <!-- Nilai Utama -->
            <div class="mt-5 flex items-baseline">
                <span id="stat-team-members" class="text-4xl sm:text-[42px] font-extrabold text-gray-900 tracking-tight">
                    <?= htmlspecialchars((string)$totalCategories) ?>
                </span>
                <span class="text-xs font-semibold text-gray-400 ml-2">klasifikasi</span>
            </div>
        </div>

        <!-- Baris Bawah: Tautan Kelola & Avatar / Ikon Kategori -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
            <span class="text-gray-600 font-medium hover:text-gray-900 transition cursor-pointer">
                Kelola Kategori
            </span>

            <div class="flex items-center -space-x-1.5">
                <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px] ring-2 ring-white z-20">
                    M
                </span>
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-[10px] ring-2 ring-white z-10">
                    D
                </span>
                <span class="w-7 h-7 rounded-full bg-pink-100 text-pink-700 font-bold flex items-center justify-center text-[10px] ring-2 ring-white">
                    E
                </span>
            </div>
        </div>
    </div>
</section>
