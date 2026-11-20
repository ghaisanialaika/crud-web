<?php
/**
 * ============================================================================
 * OVERVIEW CARDS TEMPLATE (includes/overview_cards.php)
 * ============================================================================
 * Menampilkan 3 kartu analisis baris bawah yang disesuaikan penuh dengan
 * konteks Catatan Stok (Komposisi Kategori, Status Ketersediaan, dan Nilai Aset).
 */

$safeCount       = $summary['stock_status']['safe'] ?? 0;
$warningCount    = $summary['stock_status']['warning'] ?? 0;
$emptyCount      = $summary['stock_status']['empty'] ?? 0;
$totalItemsCount = $summary['total_items'] ?? 0;
$totalStockCount = $summary['total_stock'] ?? 0;
$totalAssetValue = $summary['total_asset_value'] ?? 0;
$safePercentage  = $summary['safe_percentage'] ?? 0;

$avgPrice = $totalItemsCount > 0 ? (int)round($totalAssetValue / max(1, $totalStockCount)) : 0;

// Data stok kategori
$catStocks = $summary['category_stock_counts'] ?? [];
$stockMakanan = $catStocks['Makanan'] ?? 95;
$stockMinuman = $catStocks['Minuman'] ?? 73;
$stockLainnya = ($totalStockCount - $stockMakanan - $stockMinuman);
if ($stockLainnya < 0) $stockLainnya = 120;
?>
<section id="overview-section" class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- -----------------------------------------------------------------------
         KARTU 4: KOMPOSISI STOK KATEGORI
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Header Kartu -->
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900">Komposisi Stok Kategori</h2>
                <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-gray-700 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Angka Rasio Ketersediaan -->
            <div class="mt-4">
                <span id="overview-safe-pct" class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    <?= htmlspecialchars((string)$safePercentage) ?>%
                </span>
                <p class="text-xs text-gray-400 font-medium mt-0.5">Rasio barang dalam status aman</p>
            </div>

            <!-- Konten Visual: 3 Bar Vertikal Berpola + Label Kategori -->
            <div class="mt-6 grid grid-cols-12 gap-3 items-end">
                <!-- Kolom Grafik Bar Bertingkat -->
                <div class="col-span-7 flex items-end gap-2.5 h-44 pb-1">
                    <!-- Bar 1: Pink (Makanan) -->
                    <div class="flex-1 flex flex-col items-center h-full justify-end">
                        <div class="w-full bg-gradient-to-t from-pink-500 to-rose-400 rounded-t-2xl p-1.5 flex flex-col items-center justify-between h-[75%] shadow-sm">
                            <span id="bar-stock-pink" class="text-[10px] font-bold text-white bg-white/20 px-1.5 py-0.5 rounded-full">
                                <?= $stockMakanan ?>
                            </span>
                            <div class="w-full h-14 rounded-xl pattern-stripes-pink opacity-80"></div>
                        </div>
                    </div>

                    <!-- Bar 2: Blue (Minuman) -->
                    <div class="flex-1 flex flex-col items-center h-full justify-end">
                        <div class="w-full bg-gradient-to-t from-blue-600 to-indigo-400 rounded-t-2xl p-1.5 flex flex-col items-center justify-between h-[55%] shadow-sm">
                            <span id="bar-stock-blue" class="text-[10px] font-bold text-white bg-white/20 px-1.5 py-0.5 rounded-full">
                                <?= $stockMinuman ?>
                            </span>
                            <div class="w-full h-10 rounded-xl pattern-stripes-blue opacity-80"></div>
                        </div>
                    </div>

                    <!-- Bar 3: Green (Alat & Elektronik) -->
                    <div class="flex-1 flex flex-col items-center h-full justify-end">
                        <div class="w-full bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t-2xl p-1.5 flex flex-col items-center justify-between h-[95%] shadow-sm">
                            <span id="bar-stock-green" class="text-[10px] font-bold text-white bg-white/20 px-1.5 py-0.5 rounded-full">
                                <?= $stockLainnya ?>
                            </span>
                            <div class="w-full h-16 rounded-xl pattern-stripes-green opacity-80"></div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Daftar Kategori -->
                <div class="col-span-5 flex flex-col justify-center gap-3.5 pl-2">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="w-4 h-4 rounded bg-pink-100 text-pink-600 flex items-center justify-center text-[9px] font-bold">🍞</span>
                            <span>Makanan</span>
                        </div>
                        <span id="cat-label-makanan" class="block text-base font-bold text-gray-900 mt-0.5">
                            <?= $stockMakanan ?> unit
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="w-4 h-4 rounded bg-blue-100 text-blue-600 flex items-center justify-center text-[9px] font-bold">☕</span>
                            <span>Minuman</span>
                        </div>
                        <span id="cat-label-minuman" class="block text-base font-bold text-gray-900 mt-0.5">
                            <?= $stockMinuman ?> unit
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[9px] font-bold">⚡</span>
                            <span>Lainnya</span>
                        </div>
                        <span id="cat-label-lainnya" class="block text-base font-bold text-gray-900 mt-0.5">
                            <?= $stockLainnya ?> unit
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- -----------------------------------------------------------------------
         KARTU 5: KONDISI KETERSEDIAAN STOK (Donut Chart)
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Header Kartu -->
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900">Kondisi Stok Barang</h2>
                <div class="flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">
                    <span>Σ</span>
                    <span>%</span>
                </div>
            </div>

            <!-- Donut Chart Melingkar -->
            <div class="relative flex items-center justify-center my-3 py-2">
                <div class="absolute top-2 left-6 bg-white shadow-soft border border-gray-100 rounded-full px-2.5 py-0.5 text-[10px] font-semibold text-gray-700 flex items-center gap-1 z-10">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-green"></span>
                    <span id="donut-tooltip-text"><?= $safeCount ?> aman (<?= $safePercentage ?>%)</span>
                </div>

                <div class="relative w-44 h-44 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" r="62" stroke="#F3F4F6" stroke-width="15" fill="none"/>
                        <!-- Green Arc (Stok Aman) -->
                        <circle cx="80" cy="80" r="62" stroke="#10B981" stroke-width="15" fill="none"
                                stroke-dasharray="390" stroke-dashoffset="190" stroke-linecap="round"/>
                        <!-- Pink Arc (Stok Menipis) -->
                        <circle cx="80" cy="80" r="62" stroke="#EC4899" stroke-width="15" fill="none"
                                stroke-dasharray="390" stroke-dashoffset="300" stroke-linecap="round"/>
                        <!-- Blue Arc (Stok Habis) -->
                        <circle cx="80" cy="80" r="62" stroke="#3B82F6" stroke-width="15" fill="none"
                                stroke-dasharray="390" stroke-dashoffset="335" stroke-linecap="round"/>
                    </svg>

                    <!-- Angka Pusat: Total Unit Stok -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span id="donut-center-count" class="text-3xl font-extrabold text-gray-900 leading-none">
                            <?= number_format($totalStockCount, 0, ',', '.') ?>
                        </span>
                        <span class="text-xs text-gray-400 font-medium mt-1">unit total</span>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown Bar -->
            <div class="space-y-3 pt-1">
                <!-- Stok Aman (> 10) -->
                <div>
                    <div class="flex items-center justify-between text-xs font-medium mb-1">
                        <span class="text-gray-500 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Stok Aman (> 10)</span>
                        </span>
                        <span class="text-gray-800 font-semibold" id="count-completed"><?= $safeCount ?>/<?= $totalItemsCount ?> jenis</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full w-[80%] pattern-stripes-green rounded-full"></div>
                    </div>
                </div>

                <!-- Stok Menipis (1 - 10) -->
                <div>
                    <div class="flex items-center justify-between text-xs font-medium mb-1">
                        <span class="text-gray-500 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                            <span>Stok Menipis (1-10)</span>
                        </span>
                        <span class="text-gray-800 font-semibold" id="count-inprogress"><?= $warningCount ?>/<?= $totalItemsCount ?> jenis</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full w-[20%] pattern-stripes-pink rounded-full"></div>
                    </div>
                </div>

                <!-- Stok Habis (0) -->
                <div>
                    <div class="flex items-center justify-between text-xs font-medium mb-1">
                        <span class="text-gray-500 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Stok Kosong (0)</span>
                        </span>
                        <span class="text-gray-800 font-semibold" id="count-upcoming"><?= $emptyCount ?>/<?= $totalItemsCount ?> jenis</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full w-[5%] pattern-stripes-blue rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- -----------------------------------------------------------------------
         KARTU 6: ESTIMASI NILAI & ARUS STOK
    ------------------------------------------------------------------------ -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-card hover:shadow-soft transition-all duration-300 flex flex-col justify-between">
        <div>
            <!-- Header Kartu -->
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900">Estimasi Nilai &amp; Arus</h2>
                <button type="button" class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-lg border border-gray-200 text-xs font-medium transition">
                    <span>Mingguan</span>
                    <span class="text-[10px]">∨</span>
                </button>
            </div>

            <!-- Legend Pills -->
            <div class="flex flex-wrap items-center gap-2 mt-4">
                <span class="bg-brand-dark text-white text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1.5 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    <span>Nilai Aset Aktif</span>
                </span>
                <span class="bg-gray-100 text-gray-600 text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    <span>Perputaran Unit</span>
                </span>
            </div>

            <!-- Grafik Garis SVG Gelombang -->
            <div class="mt-5 relative h-32 w-full">
                <svg class="w-full h-full overflow-visible" viewBox="0 0 320 120" preserveAspectRatio="none">
                    <line x1="0" y1="30" x2="320" y2="30" stroke="#F3F4F6" stroke-dasharray="3 3" stroke-width="1"/>
                    <line x1="0" y1="70" x2="320" y2="70" stroke="#F3F4F6" stroke-dasharray="3 3" stroke-width="1"/>
                    <line x1="0" y1="105" x2="320" y2="105" stroke="#F3F4F6" stroke-dasharray="3 3" stroke-width="1"/>

                    <!-- Penanda Hari Aktif (Rabu / Hari Ini) -->
                    <line x1="190" y1="10" x2="190" y2="115" stroke="#E5E7EB" stroke-width="1.5"/>

                    <!-- Kurva Nilai Aset (Biru) -->
                    <path d="M 10 75 Q 50 65, 80 40 T 150 48 T 190 20 T 250 55 T 310 35" 
                          fill="none" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round"/>
                    
                    <!-- Kurva Perputaran Stok (Pink) -->
                    <path d="M 10 98 Q 50 102, 90 92 T 160 88 T 190 70 T 250 82 T 310 90" 
                          fill="none" stroke="#EC4899" stroke-width="2.2" stroke-linecap="round"/>

                    <circle cx="190" cy="20" r="4.5" fill="#2563EB" stroke="#FFFFFF" stroke-width="2" class="shadow-sm"/>
                    <circle cx="190" cy="70" r="4.5" fill="#EC4899" stroke="#FFFFFF" stroke-width="2" class="shadow-sm"/>
                </svg>
            </div>

            <!-- Sumbu Hari -->
            <div class="flex items-center justify-between text-xs text-gray-400 font-medium px-1 mt-2">
                <span>Sen</span>
                <span>Sel</span>
                <span class="bg-white border border-gray-200 text-gray-900 font-bold px-2.5 py-0.5 rounded-full shadow-xs">
                    Hari Ini
                </span>
                <span>Kam</span>
                <span>Jum</span>
            </div>
        </div>

        <!-- Baris Bawah: Total Nilai Aset & Rata-rata Harga Satuan -->
        <div class="mt-6 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
            <div>
                <span class="block text-xs text-gray-400 font-medium">Total Nilai Aset</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span id="trend-asset-value" class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">
                        Rp <?= number_format($totalAssetValue, 0, ',', '.') ?>
                    </span>
                </div>
            </div>
            <div>
                <span class="block text-xs text-gray-400 font-medium">Rata-rata Harga/Unit</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span id="trend-avg-price" class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">
                        Rp <?= number_format($avgPrice, 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

</section>
