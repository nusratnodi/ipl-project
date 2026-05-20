const API = 'api/products.php';

const form        = document.getElementById('product-form');
const formTitle   = document.getElementById('form-title');
const idInput     = document.getElementById('product-id');
const nameInput   = document.getElementById('name');
const priceInput  = document.getElementById('price');
const descInput   = document.getElementById('description');
const submitBtn   = document.getElementById('submit-btn');
const cancelBtn   = document.getElementById('cancel-btn');
const refreshBtn  = document.getElementById('refresh-btn');
const tbody       = document.getElementById('products-tbody');
const message     = document.getElementById('form-message');

function showMessage(text, type = 'success') {
  message.textContent = text;
  message.className = 'message ' + type;
  if (text) setTimeout(() => { message.textContent = ''; message.className = 'message'; }, 3000);
}

function escapeHtml(s) {
  if (s == null) return '';
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function resetForm() {
  form.reset();
  idInput.value = '';
  formTitle.textContent = 'Add Product';
  submitBtn.textContent = 'Save';
  cancelBtn.classList.add('hidden');
}

async function apiCall(action, options = {}) {
  const url = `${API}?action=${action}` + (options.id ? `&id=${options.id}` : '');
  const init = { method: options.method || 'GET' };
  if (options.body) {
    init.headers = { 'Content-Type': 'application/json' };
    init.body = JSON.stringify(options.body);
  }
  const res = await fetch(url, init);
  const json = await res.json().catch(() => ({ ok: false, error: 'Invalid JSON response' }));
  if (!res.ok || !json.ok) throw new Error(json.error || `Request failed (${res.status})`);
  return json;
}

async function loadProducts() {
  tbody.innerHTML = '<tr><td colspan="5" class="empty">Loading…</td></tr>';
  try {
    const { data } = await apiCall('list');
    renderProducts(data);
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="5" class="empty">Error: ${escapeHtml(err.message)}</td></tr>`;
  }
}

function renderProducts(items) {
  if (!items.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="empty">No products yet — add one above.</td></tr>';
    return;
  }
  tbody.innerHTML = items.map(p => `
    <tr>
      <td>${p.id}</td>
      <td>${escapeHtml(p.name)}</td>
      <td>$${Number(p.price).toFixed(2)}</td>
      <td>${escapeHtml(p.description || '')}</td>
      <td>
        <button class="btn btn-edit" data-edit="${p.id}">Edit</button>
        <button class="btn btn-delete" data-delete="${p.id}">Delete</button>
      </td>
    </tr>
  `).join('');
}

async function startEdit(id) {
  try {
    const { data } = await apiCall('get', { id });
    idInput.value     = data.id;
    nameInput.value   = data.name;
    priceInput.value  = data.price;
    descInput.value   = data.description || '';
    formTitle.textContent = `Edit Product #${data.id}`;
    submitBtn.textContent = 'Update';
    cancelBtn.classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } catch (err) {
    showMessage(err.message, 'error');
  }
}

async function deleteProduct(id) {
  if (!confirm(`Delete product #${id}?`)) return;
  try {
    await apiCall('delete', { method: 'POST', body: { id: Number(id) } });
    showMessage('Product deleted.', 'success');
    loadProducts();
  } catch (err) {
    showMessage(err.message, 'error');
  }
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = {
    name: nameInput.value.trim(),
    price: parseFloat(priceInput.value) || 0,
    description: descInput.value.trim(),
  };
  const id = idInput.value;
  try {
    if (id) {
      payload.id = Number(id);
      await apiCall('update', { method: 'POST', body: payload });
      showMessage('Product updated.', 'success');
    } else {
      await apiCall('create', { method: 'POST', body: payload });
      showMessage('Product added.', 'success');
    }
    resetForm();
    loadProducts();
  } catch (err) {
    showMessage(err.message, 'error');
  }
});

cancelBtn.addEventListener('click', resetForm);
refreshBtn.addEventListener('click', loadProducts);

tbody.addEventListener('click', (e) => {
  const editId = e.target.dataset.edit;
  const delId  = e.target.dataset.delete;
  if (editId) startEdit(editId);
  if (delId)  deleteProduct(delId);
});

loadProducts();
