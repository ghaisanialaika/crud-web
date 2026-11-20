/**
 * ============================================================================
 * JAVASCRIPT LOGIKA APLIKASI (assets/js/app.js)
 * ============================================================================
 * File ini mengatur seluruh interaksi di layar pengguna:
 * 1. Mengambil data dari API backend (PHP) menggunakan fetch().
 * 2. Merender baris tabel inventaris dan memperbarui angka ringkasan dasbor.
 * 3. Fitur live search (pencarian langsung saat mengetik).
 * 4. Filter kategori berdasarkan tombol pill (Semua, Makanan, Minuman, dll).
 * 5. Membuka & menutup modal Tambah, Ubah, dan Hapus barang.
 * 6. Mengirim data Create, Update, dan Delete ke backend secara asynchronous.
 * 7. Menampilkan toast notification (pemberitahuan mengambang).
 */

// Konstanta URL Endpoint API Backend
const API_URL = 'api.php';

// Variabel State (Penyimpanan data lokal di memori browser)
let globalItems = [];            // Menyimpan seluruh daftar barang dari server
let currentCategoryFilter = 'all'; // Kategori yang sedang aktif difilter
let activeDeleteTarget = null;   // Menyimpan objek barang yang sedang akan dihapus
let toastTimeoutId = null;       // Penampung timer agar toast hilang otomatis

// Referensi Elemen DOM
const searchInput          = document.getElementById('search-input');
const inventoryTbody       = document.getElementById('inventory-tbody');
const emptyStateContainer   = document.getElementById('empty-state');
const badgeTotalItems      = document.getElementById('badge-total-items');
const footerResultCount    = document.getElementById('footer-result-count');
const categoryFilterBtns   = document.querySelectorAll('.category-filter-btn');

// Referensi Dialog & Formulir
const itemDialog           = document.getElementById('item-dialog');
const itemForm             = document.getElementById('item-form');
const dialogEyebrow        = document.getElementById('dialog-eyebrow');
const dialogTitle          = document.getElementById('dialog-title');
const submitDialogBtn      = document.getElementById('submit-dialog-btn');
const submitDialogText     = document.getElementById('submit-dialog-text');
const formErrorAlert       = document.getElementById('form-error-alert');
const formErrorMessage     = document.getElementById('form-error-message');

const deleteDialog         = document.getElementById('delete-dialog');
const deleteDialogText     = document.getElementById('delete-dialog-text');
const confirmDeleteBtn     = document.getElementById('confirm-delete-btn');

// Referensi Toast
const toastNotification    = document.getElementById('toast-notification');
const toastMessage         = document.getElementById('toast-message');
const toastIcon            = document.getElementById('toast-icon');

// ---------------------------------------------------------------------------
// FUNGSI PEMBANTU (HELPER UTILS)
// ---------------------------------------------------------------------------

/**
 * Format angka ke format mata uang Rupiah Indonesia (contoh: Rp 50.000)
 */
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(Number(number) || 0);
}

/**
 * Format angka standar dengan pemisah ribuan (contoh: 1.250)
 */
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(Number(number) || 0);
}

/**
 * Mencegah XSS (Cross Site Scripting) dengan membersihkan karakter khusus HTML
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}

/**
 * Menampilkan notifikasi Toast mengambang
 */
function showToast(message, isError = false) {
    clearTimeout(toastTimeoutId);

    toastMessage.textContent = message;

    if (isError) {
        toastNotification.classList.remove('bg-brand-dark');
        toastNotification.classList.add('bg-rose-600');
        toastIcon.textContent = '✕';
        toastIcon.className = 'w-4 h-4 text-white flex items-center justify-center font-bold';
    } else {
        toastNotification.classList.remove('bg-rose-600');
        toastNotification.classList.add('bg-brand-dark');
        toastIcon.textContent = '✓';
        toastIcon.className = 'w-4 h-4 text-emerald-400 flex items-center justify-center font-bold';
    }

    // Tampilkan toast dengan mengubah opacity & transformasi
    toastNotification.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
    toastNotification.classList.add('opacity-100', 'translate-y-0');

    // Sembunyikan kembali setelah 3 detik
    toastTimeoutId = setTimeout(() => {
        toastNotification.classList.remove('opacity-100', 'translate-y-0');
        toastNotification.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
    }, 3000);
}

// ---------------------------------------------------------------------------
// KOMUNIKASI API (FETCH REQUEST)
// ---------------------------------------------------------------------------

/**
 * Fungsi umum untuk melakukan HTTP Request ke backend PHP
 */
async function fetchApi(action, options = {}) {
    try {
        const response = await fetch(`${API_URL}?action=${action}`, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            ...options
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Terjadi kesalahan saat memproses permintaan.');
        }

        return result;
    } catch (error) {
        throw error;
    }
}

/**
 * Mengambil seluruh data barang dari server dan memperbarui UI
 */
async function loadData() {
    try {
        const response = await fetchApi('list');
        globalItems = response.data || [];
        
        // Perbarui dasbor dan tabel
        updateDashboardWidgets(response.summary || {});
        applyFiltersAndRender();
    } catch (error) {
        console.error('Gagal memuat data inventaris:', error);
        showToast(error.message, true);
    }
}

// ---------------------------------------------------------------------------
// PEMBARUAN TAMPILAN (RENDER UI)
// ---------------------------------------------------------------------------

/**
 * Memperbarui angka statistik pada kartu-kartu dasbor
 */
function updateDashboardWidgets(summary) {
    const totalItems = summary.total_items ?? globalItems.length;
    const totalStock = summary.total_stock ?? globalItems.reduce((acc, it) => acc + Number(it.stock), 0);
    const totalCats  = summary.total_categories ?? new Set(globalItems.map(it => it.category)).size;
    const totalAsset = summary.total_asset_value ?? globalItems.reduce((acc, it) => acc + (Number(it.stock) * Number(it.price)), 0);
    const safePct    = summary.safe_percentage ?? (totalItems > 0 ? Math.round(((summary.stock_status?.safe ?? 0) / totalItems) * 100) : 0);
    const avgPrice   = totalStock > 0 ? Math.round(totalAsset / totalStock) : 0;

    // Kartu Atas
    const statActiveElem = document.getElementById('stat-active-projects');
    const statTasksElem  = document.getElementById('stat-total-tasks');
    const statTeamElem   = document.getElementById('stat-team-members');
    const donutCenterElem= document.getElementById('donut-center-count');

    if (statActiveElem) statActiveElem.textContent = formatNumber(totalItems);
    if (statTasksElem)  statTasksElem.textContent  = formatNumber(totalStock);
    if (statTeamElem)   statTeamElem.textContent   = formatNumber(totalCats);
    if (donutCenterElem)donutCenterElem.textContent= formatNumber(totalStock);

    // Update status breakdown counts
    const safeCount    = summary.stock_status?.safe ?? 0;
    const warningCount = summary.stock_status?.warning ?? 0;
    const emptyCount   = summary.stock_status?.empty ?? 0;

    const countCompletedElem  = document.getElementById('count-completed');
    const countInProgressElem = document.getElementById('count-inprogress');
    const countUpcomingElem   = document.getElementById('count-upcoming');
    const donutTooltipElem    = document.getElementById('donut-tooltip-text');
    const overviewSafePctElem = document.getElementById('overview-safe-pct');

    if (countCompletedElem)  countCompletedElem.textContent  = `${safeCount}/${totalItems} jenis`;
    if (countInProgressElem) countInProgressElem.textContent = `${warningCount}/${totalItems} jenis`;
    if (countUpcomingElem)   countUpcomingElem.textContent   = `${emptyCount}/${totalItems} jenis`;
    if (donutTooltipElem)    donutTooltipElem.textContent    = `${safeCount} aman (${safePct}%)`;
    if (overviewSafePctElem) overviewSafePctElem.textContent = `${safePct}%`;

    // Data Kategori Stok
    const catStocks = summary.category_stock_counts || {};
    const stockMakanan = catStocks['Makanan'] ?? 0;
    const stockMinuman = catStocks['Minuman'] ?? 0;
    const stockLainnya = Math.max(0, totalStock - stockMakanan - stockMinuman);

    const barPink  = document.getElementById('bar-stock-pink');
    const barBlue  = document.getElementById('bar-stock-blue');
    const barGreen = document.getElementById('bar-stock-green');

    if (barPink)  barPink.textContent  = formatNumber(stockMakanan);
    if (barBlue)  barBlue.textContent  = formatNumber(stockMinuman);
    if (barGreen) barGreen.textContent = formatNumber(stockLainnya);

    const catLblMakanan = document.getElementById('cat-label-makanan');
    const catLblMinuman = document.getElementById('cat-label-minuman');
    const catLblLainnya = document.getElementById('cat-label-lainnya');

    if (catLblMakanan) catLblMakanan.textContent = `${formatNumber(stockMakanan)} unit`;
    if (catLblMinuman) catLblMinuman.textContent = `${formatNumber(stockMinuman)} unit`;
    if (catLblLainnya) catLblLainnya.textContent = `${formatNumber(stockLainnya)} unit`;

    // Nilai Aset & Rata-rata Harga
    const trendAssetElem = document.getElementById('trend-asset-value');
    const trendAvgElem   = document.getElementById('trend-avg-price');

    if (trendAssetElem) trendAssetElem.textContent = formatRupiah(totalAsset);
    if (trendAvgElem)   trendAvgElem.textContent   = formatRupiah(avgPrice);
}

/**
 * Menerapkan pencarian teks dan filter kategori, lalu merender ke tabel
 */
function applyFiltersAndRender() {
    const searchQuery = (searchInput ? searchInput.value : '').trim().toLowerCase();

    const filtered = globalItems.filter(item => {
        // Cek kategori
        const matchesCategory = (currentCategoryFilter === 'all') || 
                               (item.category.toLowerCase() === currentCategoryFilter.toLowerCase());

        // Cek pencarian nama atau kategori
        const matchesSearch = item.name.toLowerCase().includes(searchQuery) || 
                              item.category.toLowerCase().includes(searchQuery);

        return matchesCategory && matchesSearch;
    });

    renderTable(filtered);
}

/**
 * Menghasilkan elemen baris tabel HTML berdasarkan data yang difilter
 */
function renderTable(items) {
    // Perbarui badge jumlah total
    if (badgeTotalItems) badgeTotalItems.textContent = `${globalItems.length} items`;
    if (footerResultCount) footerResultCount.textContent = `Menampilkan ${items.length} dari ${globalItems.length} barang`;

    // Tampilkan / sembunyikan empty state
    if (items.length === 0) {
        inventoryTbody.innerHTML = '';
        emptyStateContainer.classList.remove('hidden');
        return;
    }

    emptyStateContainer.classList.add('hidden');

    // Buat markup baris tabel
    const rowsHtml = items.map(item => {
        const stock = Number(item.stock) || 0;
        let badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
        let dotColor   = 'bg-emerald-500';
        let statusText = 'Aman';

        if (stock === 0) {
            badgeClass = 'bg-rose-50 text-rose-700 border-rose-100';
            dotColor   = 'bg-rose-500';
            statusText = 'Habis';
        } else if (stock <= 10) {
            badgeClass = 'bg-amber-50 text-amber-700 border-amber-100';
            dotColor   = 'bg-amber-500';
            statusText = 'Menipis';
        }

        const idFormatted = String(item.id).padStart(3, '0');

        return `
            <tr class="hover:bg-gray-50/60 transition group" data-id="${item.id}">
                <td class="py-4 px-5">
                    <div class="font-bold text-gray-900">${escapeHtml(item.name)}</div>
                    <div class="text-[11px] text-gray-400 mt-0.5">ID #${idFormatted}</div>
                </td>
                <td class="py-4 px-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        ${escapeHtml(item.category)}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ${badgeClass}">
                            <span class="w-1.5 h-1.5 rounded-full ${dotColor}"></span>
                            ${formatNumber(stock)} unit
                        </span>
                        <span class="text-[11px] text-gray-400 hidden sm:inline">(${statusText})</span>
                    </div>
                </td>
                <td class="py-4 px-4 font-semibold text-gray-900">
                    ${formatRupiah(item.price)}
                </td>
                <td class="py-4 px-5 text-right whitespace-nowrap">
                    <div class="inline-flex items-center gap-1.5">
                        <button type="button" 
                                class="btn-edit px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition"
                                data-id="${item.id}">
                            Ubah
                        </button>
                        <button type="button" 
                                class="btn-delete px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 transition"
                                data-id="${item.id}">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    inventoryTbody.innerHTML = rowsHtml;
}

// ---------------------------------------------------------------------------
// PENGELOLAAN DIALOG MODAL (TAMBAH / UBAH / HAPUS)
// ---------------------------------------------------------------------------

/**
 * Membuka modal form dalam mode Tambah Barang Baru
 */
function openCreateModal() {
    itemForm.reset();
    document.getElementById('form-item-id').value = '';
    dialogEyebrow.textContent = 'Tambah Barang Baru';
    dialogTitle.textContent = 'Tambah Barang';
    submitDialogText.textContent = 'Simpan Barang';
    formErrorAlert.classList.add('hidden');
    
    itemDialog.showModal();
    document.getElementById('item-name').focus();
}

/**
 * Membuka modal form dalam mode Ubah / Edit Barang
 */
function openEditModal(item) {
    if (!item) return;

    itemForm.reset();
    document.getElementById('form-item-id').value   = item.id;
    document.getElementById('item-name').value     = item.name;
    document.getElementById('item-category').value = item.category;
    document.getElementById('item-stock').value    = item.stock;
    document.getElementById('item-price').value    = item.price;

    dialogEyebrow.textContent = 'Perbarui Data Barang';
    dialogTitle.textContent = 'Ubah Barang';
    submitDialogText.textContent = 'Simpan Perubahan';
    formErrorAlert.classList.add('hidden');

    itemDialog.showModal();
    document.getElementById('item-name').focus();
}

/**
 * Menutup dialog form tambah/ubah
 */
function closeItemDialog() {
    itemDialog.close();
    itemForm.reset();
}

/**
 * Membuka dialog konfirmasi hapus barang
 */
function openDeleteModal(item) {
    activeDeleteTarget = item;
    deleteDialogText.innerHTML = `Barang <strong class="text-gray-900">“${escapeHtml(item.name)}”</strong> akan dihapus permanen dari inventaris.`;
    deleteDialog.showModal();
}

/**
 * Menutup dialog konfirmasi hapus
 */
function closeDeleteDialog() {
    deleteDialog.close();
    activeDeleteTarget = null;
}

// ---------------------------------------------------------------------------
// EVENT LISTENERS & PENANGANAN AKSI PENGGUNA
// ---------------------------------------------------------------------------

// 1. Tombol Buka Modal Tambah Barang
document.getElementById('open-create-modal')?.addEventListener('click', openCreateModal);
document.getElementById('quick-add-button')?.addEventListener('click', openCreateModal);
document.getElementById('empty-add-button')?.addEventListener('click', openCreateModal);

// 2. Tombol Tutup Modal Form
document.getElementById('close-dialog-btn')?.addEventListener('click', closeItemDialog);
document.getElementById('cancel-dialog-btn')?.addEventListener('click', closeItemDialog);

// 3. Tombol Batal Hapus
document.getElementById('cancel-delete-btn')?.addEventListener('click', closeDeleteDialog);

// 4. Live Search Input
if (searchInput) {
    searchInput.addEventListener('input', () => {
        applyFiltersAndRender();
    });
}

// 5. Filter Kategori Tombol Pill
categoryFilterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Hapus styling aktif dari semua tombol
        categoryFilterBtns.forEach(b => {
            b.classList.remove('bg-brand-dark', 'text-white', 'shadow-xs');
            b.classList.add('bg-gray-100', 'text-gray-600');
        });

        // Berikan styling aktif ke tombol yang diklik
        btn.classList.remove('bg-gray-100', 'text-gray-600');
        btn.classList.add('bg-brand-dark', 'text-white', 'shadow-xs');

        currentCategoryFilter = btn.dataset.category || 'all';
        applyFiltersAndRender();
    });
});

// 6. Klik pada Tombol Ubah dan Hapus di dalam Tabel (Event Delegation)
inventoryTbody.addEventListener('click', event => {
    const editBtn   = event.target.closest('.btn-edit');
    const deleteBtn = event.target.closest('.btn-delete');

    if (editBtn) {
        const id = Number(editBtn.dataset.id);
        const item = globalItems.find(it => Number(it.id) === id);
        if (item) openEditModal(item);
    }

    if (deleteBtn) {
        const id = Number(deleteBtn.dataset.id);
        const item = globalItems.find(it => Number(it.id) === id);
        if (item) openDeleteModal(item);
    }
});

// 7. Pengiriman Formulir Tambah / Ubah Barang (Form Submit)
itemForm.addEventListener('submit', async event => {
    event.preventDefault();

    const id       = document.getElementById('form-item-id').value;
    const name     = document.getElementById('item-name').value.trim();
    const category = document.getElementById('item-category').value.trim();
    const stock    = parseInt(document.getElementById('item-stock').value, 10);
    const price    = parseInt(document.getElementById('item-price').value, 10);

    const isEdit = Boolean(id);

    // Kunci tombol simpan agar tidak terjadi double submit
    submitDialogBtn.disabled = true;
    submitDialogText.textContent = 'Menyimpan...';
    formErrorAlert.classList.add('hidden');

    try {
        const payload = { name, category, stock, price };
        const action = isEdit ? `update&id=${encodeURIComponent(id)}` : 'create';
        const method = isEdit ? 'PUT' : 'POST';

        const result = await fetchApi(action, {
            method: method,
            body: JSON.stringify(payload)
        });

        // Sukses
        closeItemDialog();
        showToast(result.message || 'Barang berhasil disimpan.');
        await loadData();
    } catch (error) {
        formErrorMessage.textContent = error.message;
        formErrorAlert.classList.remove('hidden');
    } finally {
        submitDialogBtn.disabled = false;
        submitDialogText.textContent = isEdit ? 'Simpan Perubahan' : 'Simpan Barang';
    }
});

// 8. Konfirmasi Hapus Barang
confirmDeleteBtn.addEventListener('click', async () => {
    if (!activeDeleteTarget) return;

    confirmDeleteBtn.disabled = true;
    confirmDeleteBtn.textContent = 'Menghapus...';

    try {
        const result = await fetchApi(`delete&id=${encodeURIComponent(activeDeleteTarget.id)}`, {
            method: 'DELETE'
        });

        closeDeleteDialog();
        showToast(result.message || 'Barang berhasil dihapus.');
        await loadData();
    } catch (error) {
        showToast(error.message, true);
    } finally {
        confirmDeleteBtn.disabled = false;
        confirmDeleteBtn.textContent = 'Ya, Hapus';
    }
});

// ---------------------------------------------------------------------------
// INISIALISASI SAAT HALAMAN SELESAI DIMUAT
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});
