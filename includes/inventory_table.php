<?php
/**
 * ============================================================================
 * INVENTORY TABLE TEMPLATE (includes/inventory_table.php)
 * ============================================================================
 * Komponen utama manajemen CRUD inventaris barang:
 * - Menampilkan daftar barang dalam tabel modern dan elegan
 * - Kotak pencarian realtime dengan ikon pencarian (live search)
 * - Filter kategori berbasis tombol pill
 * - Tombol aksi "Ubah" dan "Hapus" untuk setiap baris barang
 * - Tampilan kosong (Empty State) jika data belum ada atau pencarian tidak cocok
 */
?>
<section id="inventory-section" class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-card flex flex-col gap-6">

    <!-- Header Tabel: Judul & Kotak Pencarian -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Daftar Inventaris Barang</h2>
                <span id="badge-total-items" class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    <?= count($items ?? []) ?> items
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Kelola stok, perbarui harga satuan, dan filter barang berdasarkan kategori.
            </p>
        </div>

        <!-- Toolbar Pencarian & Filter -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Kotak Pencarian Modern (Pill Search Input) -->
            <div class="relative flex-1 sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="search" 
                       id="search-input" 
                       placeholder="Cari nama atau kategori..." 
                       autocomplete="off"
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 hover:bg-gray-100/70 focus:bg-white text-xs sm:text-sm text-gray-800 rounded-full border border-gray-200 focus:outline-none focus:border-brand-dark transition placeholder-gray-400">
            </div>

            <!-- Tombol Tambah Barang Cepat -->
            <button type="button" 
                    id="quick-add-button" 
                    class="inline-flex items-center gap-1.5 bg-brand-dark hover:bg-black text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition active:scale-95 shadow-sm">
                <svg class="w-4 h-4 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Barang</span>
            </button>
        </div>
    </div>

    <!-- Filter Tab Kategori (Pill Chips) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-medium" id="category-filters">
        <button type="button" data-category="all" class="category-filter-btn px-4 py-1.5 rounded-full bg-brand-dark text-white shadow-xs transition">
            Semua
        </button>
        <button type="button" data-category="Makanan" class="category-filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
            Makanan
        </button>
        <button type="button" data-category="Minuman" class="category-filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
            Minuman
        </button>
        <button type="button" data-category="Alat Tulis" class="category-filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
            Alat Tulis
        </button>
        <button type="button" data-category="Elektronik" class="category-filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
            Elektronik
        </button>
    </div>

    <!-- Kontainer Tabel Data (Scrollable Responsif) -->
    <div class="overflow-x-auto rounded-2xl border border-gray-100">
        <table class="w-full text-left border-collapse" id="crud-table">
            <thead>
                <tr class="bg-gray-50/75 border-b border-gray-100 text-xs font-semibold text-gray-500">
                    <th scope="col" class="py-3.5 px-5">Nama Barang & ID</th>
                    <th scope="col" class="py-3.5 px-4">Kategori</th>
                    <th scope="col" class="py-3.5 px-4">Status & Stok</th>
                    <th scope="col" class="py-3.5 px-4">Harga Satuan</th>
                    <th scope="col" class="py-3.5 px-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="inventory-tbody" class="divide-y divide-gray-100 text-sm">
                <!-- Baris tabel akan di-render secara interaktif via JavaScript (app.js) -->
                <!-- Sebagai fallback awal saat JavaScript memuat, baris PHP dapat dirender di sini -->
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <?php 
                            $stock = (int)$item['stock'];
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                            $statusText = 'Aman';
                            if ($stock === 0) {
                                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                $statusText = 'Habis';
                            } elseif ($stock <= 10) {
                                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                $statusText = 'Menipis';
                            }
                        ?>
                        <tr class="hover:bg-gray-50/60 transition group" data-id="<?= $item['id'] ?>" data-category="<?= htmlspecialchars($item['category']) ?>">
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="text-[11px] text-gray-400 mt-0.5">ID #<?= str_pad((string)$item['id'], 3, '0', STR_PAD_LEFT) ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    <?= htmlspecialchars($item['category']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badgeClass ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $stock > 10 ? 'bg-emerald-500' : ($stock > 0 ? 'bg-amber-500' : 'bg-rose-500') ?>"></span>
                                        <?= number_format($stock, 0, ',', '.') ?> unit
                                    </span>
                                    <span class="text-[11px] text-gray-400 hidden sm:inline">(<?= $statusText ?>)</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-gray-900">
                                Rp <?= number_format((int)$item['price'], 0, ',', '.') ?>
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" 
                                            class="btn-edit px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition"
                                            data-id="<?= $item['id'] ?>">
                                        Ubah
                                    </button>
                                    <button type="button" 
                                            class="btn-delete px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 transition"
                                            data-id="<?= $item['id'] ?>"
                                            data-name="<?= htmlspecialchars($item['name']) ?>">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Empty State (Tampilan jika data kosong atau hasil cari tidak ditemukan) -->
    <div id="empty-state" class="<?= !empty($items) ? 'hidden' : '' ?> text-center py-12 px-4 flex flex-col items-center justify-center">
        <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-800">Tidak ada barang yang ditemukan</h3>
        <p class="text-xs text-gray-400 max-w-sm mt-1 mb-4">
            Coba ubah kata kunci pencarian atau tambahkan barang baru ke dalam inventaris.
        </p>
        <button type="button" 
                id="empty-add-button" 
                class="inline-flex items-center gap-1.5 bg-brand-dark hover:bg-black text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow-sm transition">
            + Tambah Barang Baru
        </button>
    </div>

    <!-- Footer Tabel: Penghitung hasil barang -->
    <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-gray-100">
        <span id="footer-result-count">Menampilkan <?= count($items ?? []) ?> barang</span>
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Database Terhubung</span>
        </span>
    </div>

</section>
