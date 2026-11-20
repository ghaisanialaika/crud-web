<?php
/**
 * ============================================================================
 * MODALS TEMPLATE (includes/modals.php)
 * ============================================================================
 * Menyediakan 2 dialog modal interaktif:
 * 1. Modal Form Tambah / Ubah Barang (#item-dialog)
 * 2. Modal Konfirmasi Hapus Barang (#delete-dialog)
 *
 * Menggunakan elemen native HTML5 <dialog> yang ringan, aksesibel, dan
 * ditata menggunakan utility class Tailwind CSS dengan teks natural (tanpa uppercase AI-slop).
 */
?>
<!-- ---------------------------------------------------------------------------
     1. MODAL TAMBAH / UBAH BARANG
---------------------------------------------------------------------------- -->
<dialog id="item-dialog" class="p-0 rounded-3xl shadow-modal bg-white border border-gray-100 max-w-lg w-[calc(100%-2rem)]">
    <div class="p-6 sm:p-8">
        <!-- Header Modal -->
        <div class="flex items-start justify-between pb-4 border-b border-gray-100">
            <div>
                <span id="dialog-eyebrow" class="text-xs font-semibold text-gray-500">
                    Tambah Barang Baru
                </span>
                <h2 id="dialog-title" class="text-xl sm:text-2xl font-bold text-gray-900 mt-0.5">
                    Tambah Barang
                </h2>
            </div>
            <!-- Tombol Tutup Silang (Icon SVG yang presisi berada di tengah tombol) -->
            <button type="button" 
                    id="close-dialog-btn" 
                    aria-label="Tutup formulir"
                    class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Formulir Input Data -->
        <form id="item-form" class="mt-6 flex flex-col gap-4">
            <!-- Hidden input untuk menyimpan ID barang jika sedang mode Ubah/Edit -->
            <input type="hidden" id="form-item-id" name="id" value="">

            <!-- Field 1: Nama Barang -->
            <div>
                <label for="item-name" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Nama Barang <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="item-name" 
                       name="name" 
                       required 
                       maxlength="100"
                       placeholder="Contoh: Kopi Robusta Lampung 250g" 
                       class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-brand-dark transition placeholder-gray-400">
            </div>

            <!-- Field 2: Kategori Barang -->
            <div>
                <label for="item-category" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="item-category" 
                       name="category" 
                       required 
                       maxlength="60"
                       placeholder="Contoh: Minuman, Makanan, Elektronik, dll." 
                       class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-brand-dark transition placeholder-gray-400">
            </div>

            <!-- Field 3 & 4: Baris Ganda (Stok & Harga) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Jumlah Stok -->
                <div>
                    <label for="item-stock" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Jumlah Stok <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           id="item-stock" 
                           name="stock" 
                           min="0" 
                           step="1" 
                           required 
                           placeholder="0" 
                           class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-brand-dark transition placeholder-gray-400">
                </div>

                <!-- Harga Satuan (Rp) -->
                <div>
                    <label for="item-price" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Harga Satuan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                            Rp
                        </span>
                        <input type="number" 
                               id="item-price" 
                               name="price" 
                               min="0" 
                               step="100" 
                               required 
                               placeholder="0" 
                               class="w-full pl-10 pr-4 py-3 rounded-2xl bg-gray-50 border border-gray-200 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-brand-dark transition placeholder-gray-400">
                    </div>
                </div>
            </div>

            <!-- Pesan Error Dinamis -->
            <div id="form-error-alert" class="hidden p-3 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span id="form-error-message">Terjadi kesalahan pada data formulir.</span>
            </div>

            <!-- Tombol Aksi Dialog -->
            <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
                <button type="button" 
                        id="cancel-dialog-btn" 
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                        id="submit-dialog-btn" 
                        class="bg-brand-dark hover:bg-black text-white px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold shadow-sm hover:shadow-md transition active:scale-95 flex items-center gap-2">
                    <span id="submit-dialog-text">Simpan Barang</span>
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- ---------------------------------------------------------------------------
     2. MODAL KONFIRMASI HAPUS BARANG
---------------------------------------------------------------------------- -->
<dialog id="delete-dialog" class="p-0 rounded-3xl shadow-modal bg-white border border-gray-100 max-w-sm w-[calc(100%-2rem)]">
    <div class="p-6 text-center">
        <!-- Lingkaran Aksen Merah Exclamation -->
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 ring-8 ring-rose-50/50">
            <svg class="w-6 h-6 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <span class="text-xs font-semibold text-rose-600">
            Konfirmasi Hapus
        </span>
        <h3 class="text-lg font-bold text-gray-900 mt-1">
            Yakin ingin menghapus?
        </h3>
        <p id="delete-dialog-text" class="text-xs text-gray-500 mt-2 leading-relaxed">
            Barang ini akan dihapus secara permanen dari database inventaris.
        </p>

        <!-- Tombol Aksi Konfirmasi Hapus -->
        <div class="flex items-center justify-center gap-2.5 mt-6">
            <button type="button" 
                    id="cancel-delete-btn" 
                    class="px-5 py-2.5 rounded-full text-xs font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                Batal
            </button>
            <button type="button" 
                    id="confirm-delete-btn" 
                    class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow-sm transition active:scale-95">
                Ya, Hapus
            </button>
        </div>
    </div>
</dialog>
