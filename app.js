const API_URL = 'api.php';
const itemList = document.querySelector('#item-list');
const itemDialog = document.querySelector('#item-dialog');
const deleteDialog = document.querySelector('#delete-dialog');
const itemForm = document.querySelector('#item-form');
const searchInput = document.querySelector('#search-input');
let items = [];
let deleteTarget = null;
let toastTimer;

const rupiah = value => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(value));
const escapeHtml = value => String(value).replace(/[&<>"']/g, char => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' })[char]);

async function request(action, options = {}) {
  const response = await fetch(`${API_URL}?action=${action}`, { headers: { 'Content-Type': 'application/json' }, ...options });
  let payload;
  try { payload = await response.json(); } catch { throw new Error('Respons server tidak bisa dibaca.'); }
  if (!response.ok || !payload.success) throw new Error(payload.message || 'Ada masalah saat menghubungi server.');
  return payload;
}

async function loadItems() {
  try { items = (await request('list')).data; renderItems(); }
  catch (error) { document.querySelector('#list-description').textContent = 'Daftar barang belum bisa dimuat'; showToast(error.message, true); }
}

function renderItems() {
  const query = searchInput.value.trim().toLocaleLowerCase('id-ID');
  const filtered = items.filter(item => `${item.name} ${item.category}`.toLocaleLowerCase('id-ID').includes(query));
  document.querySelector('#total-items').textContent = items.length;
  document.querySelector('#total-stock').textContent = new Intl.NumberFormat('id-ID').format(items.reduce((sum, item) => sum + Number(item.stock), 0));
  document.querySelector('#list-description').textContent = `${items.length} barang tercatat`;
  document.querySelector('#result-count').textContent = `${filtered.length} dari ${items.length} barang`;
  document.querySelector('#empty-state').hidden = filtered.length > 0;
  itemList.closest('table').hidden = filtered.length === 0;
  itemList.innerHTML = filtered.map(item => `
    <tr><td><span class="item-name">${escapeHtml(item.name)}</span><span class="item-id">ID #${String(item.id).padStart(3, '0')}</span></td>
    <td><span class="category-pill">${escapeHtml(item.category)}</span></td>
    <td><span class="stock-value ${Number(item.stock) < 5 ? 'stock-low' : ''}">${new Intl.NumberFormat('id-ID').format(item.stock)} unit</span></td>
    <td><span class="price-value">${rupiah(item.price)}</span></td>
    <td class="actions"><button class="text-action" data-edit="${item.id}" type="button">Ubah</button><button class="text-action delete" data-delete="${item.id}" type="button">Hapus</button></td></tr>`).join('');
}

function showToast(message, isError = false) {
  const toast = document.querySelector('#toast'); toast.textContent = message;
  toast.classList.toggle('error', isError); toast.classList.add('show'); clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
}

function openForm(item = null) {
  itemForm.reset(); document.querySelector('#item-id').value = item?.id ?? '';
  document.querySelector('#name').value = item?.name ?? ''; document.querySelector('#category').value = item?.category ?? '';
  document.querySelector('#stock').value = item?.stock ?? ''; document.querySelector('#price').value = item?.price ?? '';
  document.querySelector('#dialog-title').textContent = item ? 'Ubah barang' : 'Tambah barang';
  document.querySelector('#dialog-eyebrow').textContent = item ? 'PERBARUI CATATAN' : 'BARANG BARU';
  document.querySelector('#save-button').textContent = item ? 'Simpan perubahan' : 'Simpan barang';
  document.querySelector('#form-error').hidden = true; itemDialog.showModal(); document.querySelector('#name').focus();
}

document.querySelector('#add-button').addEventListener('click', () => openForm());
document.querySelector('#empty-add-button').addEventListener('click', () => openForm());
document.querySelector('#close-dialog').addEventListener('click', () => itemDialog.close());
document.querySelector('#cancel-dialog').addEventListener('click', () => itemDialog.close());
document.querySelector('#keep-item').addEventListener('click', () => deleteDialog.close());
searchInput.addEventListener('input', renderItems);

itemList.addEventListener('click', event => {
  const editButton = event.target.closest('[data-edit]'); const deleteButton = event.target.closest('[data-delete]');
  if (editButton) openForm(items.find(item => Number(item.id) === Number(editButton.dataset.edit)));
  if (deleteButton) { deleteTarget = items.find(item => Number(item.id) === Number(deleteButton.dataset.delete)); document.querySelector('#delete-message').textContent = `“${deleteTarget.name}” akan dihapus dari daftar barang.`; deleteDialog.showModal(); }
});

itemForm.addEventListener('submit', async event => {
  event.preventDefault(); const saveButton = document.querySelector('#save-button'); const errorBox = document.querySelector('#form-error');
  const id = document.querySelector('#item-id').value;
  const body = { name: document.querySelector('#name').value.trim(), category: document.querySelector('#category').value.trim(), stock: document.querySelector('#stock').value, price: document.querySelector('#price').value };
  saveButton.disabled = true; saveButton.textContent = 'Menyimpan…'; errorBox.hidden = true;
  try {
    const result = await request(id ? `update&id=${encodeURIComponent(id)}` : 'create', { method: id ? 'PUT' : 'POST', body: JSON.stringify(body) });
    itemDialog.close(); await loadItems(); showToast(result.message || 'Barang berhasil disimpan.');
  } catch (error) { errorBox.textContent = error.message; errorBox.hidden = false; }
  finally { saveButton.disabled = false; saveButton.textContent = id ? 'Simpan perubahan' : 'Simpan barang'; }
});

document.querySelector('#confirm-delete').addEventListener('click', async () => {
  if (!deleteTarget) return; const button = document.querySelector('#confirm-delete'); button.disabled = true;
  try { const result = await request(`delete&id=${encodeURIComponent(deleteTarget.id)}`, { method: 'DELETE' }); deleteDialog.close(); await loadItems(); showToast(result.message || 'Barang berhasil dihapus.'); }
  catch (error) { showToast(error.message, true); }
  finally { deleteTarget = null; button.disabled = false; }
});

loadItems();
