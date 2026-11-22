<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý sản phẩm</title>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.21/vue.global.prod.min.js"></script>

  <style>
    /* ── Reset & base  */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      color: #333;
      padding: 24px 16px;
    }

    h1 {
      font-size: 1.6rem;
      margin-bottom: 24px;
      color: #1a1a2e;
    }

    /* ── ảnh báo  */
    .alert {
      padding: 10px 16px;
      border-radius: 6px;
      margin-bottom: 16px;
      font-size: .9rem;
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    /* ── Card  */
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,.08);
      padding: 20px 24px;
      margin-bottom: 24px;
    }
    .card h2 { font-size: 1.1rem; margin-bottom: 16px; color: #374151; }

    /* ── Form */
    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: flex-end;
    }
    .form-group { display: flex; flex-direction: column; gap: 4px; flex: 1 1 160px; }
    .form-group label { font-size: .82rem; color: #6b7280; font-weight: 600; }
    .form-group input {
      padding: 8px 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: .95rem;
      outline: none;
      transition: border-color .2s;
    }
    .form-group input:focus { border-color: #6366f1; }
    .image-preview {
      display: block;
      width: 96px;
      height: 72px;
      object-fit: cover;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      margin-top: 6px;
    }
    .image-empty { color: #9ca3af; font-size: .85rem; margin-top: 6px; }

    /* ── Buttons */
    .btn {
      padding: 8px 18px;
      border: none;
      border-radius: 6px;
      font-size: .9rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .15s, transform .1s;
      white-space: nowrap;
    }
    .btn:active { transform: scale(.97); }
    .btn:disabled { opacity: .5; cursor: not-allowed; }

    .btn-primary  { background: #6366f1; color: #fff; }
    .btn-primary:hover:not(:disabled)  { background: #4f46e5; }
    .btn-secondary { background: #e5e7eb; color: #374151; }
    .btn-secondary:hover:not(:disabled) { background: #d1d5db; }
    .btn-warning  { background: #f59e0b; color: #fff; padding: 5px 12px; font-size: .82rem; }
    .btn-warning:hover  { background: #d97706; }
    .btn-danger   { background: #ef4444; color: #fff; padding: 5px 12px; font-size: .82rem; }
    .btn-danger:hover   { background: #dc2626; }

    .btn-row { display: flex; gap: 8px; margin-top: 4px; }

    /* ── Table */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .93rem; }
    thead tr { background: #f3f4f6; }
    th, td { padding: 11px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { font-weight: 700; color: #374151; }
    tbody tr:hover { background: #fafafa; }
    td.price, td.stock { text-align: right; font-variant-numeric: tabular-nums; }
    th.price, th.stock { text-align: right; }

    /* Dòng đang được chọn để sửa */
    tbody tr.editing { background: #eef2ff; }

    /* Trạng thái loading / empty */
    .state-row td { text-align: center; color: #9ca3af; padding: 24px; }
    .pagination {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 14px;
      margin-top: 18px;
      color: #4b5563;
    }
    .product-thumbnail {
      width: 64px;
      height: 48px;
      object-fit: cover;
      border-radius: 4px;
    }

    /* Spinner */
    .spinner {
      display: inline-block;
      width: 18px; height: 18px;
      border: 2px solid #c7d2fe;
      border-top-color: #6366f1;
      border-radius: 50%;
      animation: spin .6s linear infinite;
      vertical-align: middle;
      margin-right: 6px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>

<div id="app">
  <h1>📦 Quản lý sản phẩm</h1>

  <!-- Thông báo kết quả -->
  <div v-if="alert.msg"
       :class="['alert', alert.type === 'success' ? 'alert-success' : 'alert-danger']">
    {{ alert.msg }}
  </div>

  <!-- ── Form thêm / sửa  -->
  <div class="card">
    <h2>{{ editingId ? '✏️ Sửa sản phẩm (id = ' + editingId + ')' : '➕ Thêm sản phẩm mới' }}</h2>
    <form @submit.prevent="saveProduct">
      <div class="form-row">
        <div class="form-group">
          <label for="f-name">Tên sản phẩm</label>
          <input id="f-name" v-model.trim="form.name"
                 placeholder="VD: Bút bi" maxlength="100" />
        </div>
        <div class="form-group">
          <label for="f-price">Đơn giá (đồng)</label>
          <input id="f-price" v-model.number="form.price"
                 type="number" min="1" step="1"
                 placeholder="VD: 5000" />
        </div>
        <div class="form-group">
          <label for="f-stock">Số lượng tồn</label>
          <input id="f-stock" v-model.number="form.stock"
                 type="number" min="0" step="1"
                 placeholder="VD: 20" />
        </div>
        <div class="form-group">
          <label for="f-image">Ảnh sản phẩm (JPG, JPEG, PNG; tối đa 2 MB)</label>
          <input id="f-image" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                 @change="handleImageChange" />
          <img v-if="imagePreview" :src="imagePreview" alt="Xem trước ảnh sản phẩm" class="image-preview" />
          <span v-else class="image-empty">Chưa có ảnh</span>
        </div>
        <div class="btn-row">
          <button type="submit" class="btn btn-primary" :disabled="saving || uploading">
            <span v-if="saving" class="spinner"></span>
            {{ saving ? 'Đang lưu…' : 'Lưu' }}
          </button>
          <button v-if="retryUploadId" type="button" class="btn btn-secondary"
                  :disabled="uploading" @click="retryUpload">
            {{ uploading ? 'Đang tải ảnh…' : 'Thử tải ảnh lại' }}
          </button>
          <button v-if="editingId" type="button"
                  class="btn btn-secondary" :disabled="saving || uploading" @click="cancelEdit">
            Hủy
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- ── Bảng danh sách  -->
  <div class="card">
    <h2>📋 Danh sách sản phẩm</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th class="price">Đơn giá</th>
            <th class="stock">Tồn kho</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <!-- Đang tải -->
          <tr v-if="loading" class="state-row">
            <td colspan="6"><span class="spinner"></span> Đang tải dữ liệu…</td>
          </tr>
          <!-- Chưa có sản phẩm -->
          <tr v-else-if="products.length === 0" class="state-row">
            <td colspan="6">Chưa có sản phẩm</td>
          </tr>
          <!-- Danh sách -->
          <tr v-else
              v-for="p in products" :key="p.id"
              :class="{ editing: editingId === p.id }">
            <td>{{ p.id }}</td>
            <td>{{ p.name }}</td>
            <td class="price">{{ formatPrice(p.price) }}</td>
            <td class="stock">{{ p.stock }}</td>
            <td>
              <img v-if="p.image" :src="p.image" :alt="'Ảnh ' + p.name" class="product-thumbnail" />
              <span v-else>Chưa có ảnh</span>
            </td>
            <td>
              <div class="btn-row">
                <button class="btn btn-warning" @click="startEdit(p)">Sửa</button>
                <button class="btn btn-danger"  @click="deleteProduct(p)">Xóa</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <button class="btn btn-secondary" :disabled="loading || pagination.page <= 1"
              @click="fetchProducts(pagination.page - 1)">Trước</button>
      <span>Trang {{ pagination.page }} / {{ pagination.total_pages }} — Tổng {{ pagination.total }} sản phẩm</span>
      <button class="btn btn-secondary"
              :disabled="loading || pagination.page >= pagination.total_pages"
              @click="fetchProducts(pagination.page + 1)">Sau</button>
    </div>
  </div>
</div>

<script>
const { createApp, ref, reactive, onMounted } = Vue;

// ── Địa chỉ API 
// Nếu index.php nằm trong app/, API nằm trong app/api/products.php
const API_URL = 'api/products.php';

createApp({
  setup() {

    // ── Trạng thái 
    const products  = ref([]);
    const loading   = ref(false);
    const saving    = ref(false);
    const uploading = ref(false);
    const editingId = ref(null);    // null = chế độ thêm mới
    const pagination = reactive({ page: 1, limit: 5, total: 0, total_pages: 1 });
    const selectedImage = ref(null);
    const imagePreview = ref('');
    const currentImage = ref(null);
    const retryUploadId = ref(null);
    const retryPage = ref(1);

    const form = reactive({ name: '', price: '', stock: '' });

    const alert = reactive({ msg: '', type: 'success' });
    let alertTimer = null;

    // ── Hiển thị thông báo có tự đóng 
    function showAlert(msg, type = 'success', ms = 4000) {
      clearTimeout(alertTimer);
      alert.msg  = msg;
      alert.type = type;
      if (ms > 0) {
        alertTimer = setTimeout(() => { alert.msg = ''; }, ms);
      }
    }

    // ── Format giá tiền 
    function formatPrice(val) {
      return new Intl.NumberFormat('vi-VN').format(val) + ' đ';
    }

    // ── Lấy danh sách sản phẩm 
    async function fetchProducts(page = pagination.page) {
      loading.value = true;
      try {
        const res  = await fetch(`${API_URL}?page=${page}&limit=5`);
        const json = await res.json();
        if (res.ok && json.success) {
          products.value = json.data;
          Object.assign(pagination, json.pagination);
        } else {
          showAlert('Lỗi tải danh sách: ' + (json.message || 'Không xác định'), 'error', 0);
        }
      } catch (e) {
        showAlert('Không thể kết nối đến API.', 'error', 0);
      } finally {
        loading.value = false;
      }
    }

    function clearSelectedImage() {
      if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
      }
      selectedImage.value = null;
      imagePreview.value = currentImage.value || '';
      const input = document.getElementById('f-image');
      if (input) input.value = '';
    }

    function handleImageChange(event) {
      const input = event.target;
      const file = input.files && input.files[0];
      if (!file) return;

      const extensionAllowed = /\.(jpe?g|png)$/i.test(file.name);
      const typeAllowed = ['image/jpeg', 'image/png'].includes(file.type);
      if (!extensionAllowed || !typeAllowed) {
        clearSelectedImage();
        showAlert('Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.', 'error');
        return;
      }
      if (file.size < 1 || file.size > 2 * 1024 * 1024) {
        clearSelectedImage();
        showAlert('Ảnh phải có dung lượng lớn hơn 0 và không vượt quá 2 MB.', 'error');
        return;
      }

      if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
      }
      selectedImage.value = file;
      imagePreview.value = URL.createObjectURL(file);
    }

    // ── Reset form về trạng thái thêm mới 
    function resetForm() {
      clearSelectedImage();
      form.name  = '';
      form.price = '';
      form.stock = '';
      editingId.value = null;
      currentImage.value = null;
      imagePreview.value = '';
      retryUploadId.value = null;
    }

    // ── Bấm Sửa → đưa dữ liệu lên form
    function startEdit(product) {
      clearSelectedImage();
      editingId.value = product.id;
      form.name  = product.name;
      form.price = product.price;
      form.stock = product.stock;
      currentImage.value = product.image;
      imagePreview.value = product.image || '';
      retryUploadId.value = null;
      // Cuộn lên đầu để thấy form
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── Bấm Hủy 
    function cancelEdit() {
      resetForm();
    }

    async function uploadSelectedImage(productId) {
      if (!selectedImage.value) return false;

      uploading.value = true;
      const payload = new FormData();
      payload.append('image', selectedImage.value);
      payload.append('product_id', String(productId));

      try {
        const res = await fetch('api/upload.php', { method: 'POST', body: payload });
        const json = await res.json();
        if (!res.ok || !json.success) {
          throw new Error(json.message || 'Không thể tải ảnh lên.');
        }
        currentImage.value = json.data.image;
        return true;
      } catch (e) {
        showAlert('Thông tin sản phẩm đã lưu nhưng tải ảnh thất bại: ' +
          (e.message || 'Không thể kết nối đến API.'), 'error', 0);
        retryUploadId.value = productId;
        return false;
      } finally {
        uploading.value = false;
      }
    }

    async function retryUpload() {
      if (!retryUploadId.value || !selectedImage.value) {
        showAlert('Vui lòng chọn ảnh trước khi thử tải lại.', 'error');
        return;
      }
      const uploaded = await uploadSelectedImage(retryUploadId.value);
      if (uploaded) {
        showAlert('Đã tải ảnh lên thành công.');
        resetForm();
        await fetchProducts(retryPage.value);
      }
    }

    // ── Lưu (thêm hoặc sửa)
    async function saveProduct() {
      // Kiểm tra tối giản phía client trước khi gọi API
      if (!form.name) {
        showAlert('Vui lòng nhập tên sản phẩm.', 'error');
        return;
      }
      if (!Number.isInteger(form.price) || form.price <= 0) {
        showAlert('Đơn giá phải là số nguyên lớn hơn 0.', 'error');
        return;
      }
      if (!Number.isInteger(form.stock) || form.stock < 0) {
        showAlert('Số lượng tồn phải là số nguyên không âm.', 'error');
        return;
      }

      saving.value = true;
      const isEdit  = editingId.value !== null;
      const url     = isEdit ? `${API_URL}?id=${editingId.value}` : API_URL;
      const method  = isEdit ? 'PUT' : 'POST';
      const payload = {
        name:  form.name,
        price: form.price,
        stock: form.stock,
      };

      try {
        const res  = await fetch(url, {
          method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });
        const json = await res.json();

        if (res.ok && json.success) {
          const savedProductId = Number(json.data.id);
          const pageAfterSave = retryUploadId.value
            ? retryPage.value
            : (isEdit ? pagination.page : 1);
          if (selectedImage.value) {
            if (!isEdit) editingId.value = savedProductId;
            retryPage.value = pageAfterSave;
            const uploaded = await uploadSelectedImage(savedProductId);
            if (!uploaded) return;
          }
          showAlert(
            isEdit
              ? `Đã cập nhật sản phẩm "${json.data.name}" thành công.`
              : `Đã thêm sản phẩm "${json.data.name}" thành công.`
          );
          resetForm();
          await fetchProducts(pageAfterSave);
        } else {
          showAlert('Lỗi: ' + (json.message || 'Không xác định'), 'error');
        }
      } catch (e) {
        showAlert('Không thể kết nối đến API.', 'error');
      } finally {
        saving.value = false;
      }
    }

    // ── Xóa sản phẩm 
    async function deleteProduct(product) {
      // Yêu cầu xác nhận trước khi xóa
      if (!confirm(`Bạn có chắc muốn xóa sản phẩm "${product.name}" không?`)) {
        return;
      }

      try {
        const res  = await fetch(`${API_URL}?id=${product.id}`, { method: 'DELETE' });
        const json = await res.json();

        if (json.success) {
          showAlert(`Đã xóa sản phẩm "${product.name}" thành công.`);
          // Nếu đang sửa sản phẩm này thì reset form
          if (editingId.value === product.id) {
            resetForm();
          }
          await fetchProducts();   // Làm mới bảng
        } else {
          showAlert('Lỗi xóa: ' + (json.message || 'Không xác định'), 'error');
        }
      } catch (e) {
        showAlert('Không thể kết nối đến API.', 'error');
      }
    }

    // ── Tải dữ liệu khi mở trang
    onMounted(fetchProducts);

    return {
      products, loading, saving, editingId,
      uploading, pagination, imagePreview, retryUploadId,
      form, alert,
      formatPrice,
      startEdit, cancelEdit, saveProduct, deleteProduct,
      handleImageChange, retryUpload, fetchProducts,
    };
  }
}).mount('#app');
</script>

</body>
</html>
