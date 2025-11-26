<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-box-seam"></i> 產品管理</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" onclick="openAddModal()">
                <i class="bi bi-plus-lg"></i> 新增產品
            </button>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="productTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>產品名稱</th>
                                <th>價格</th>
                                <th>類別</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">載入中...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 產品表單 Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">新增產品</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" novalidate>
                    <input type="hidden" id="productId">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">產品名稱 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback">請輸入產品名稱</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">價格 <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">NT$</span>
                            <input type="number" class="form-control" id="price" min="0" step="0.01" required>
                            <div class="invalid-feedback">請輸入有效的價格</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">類別 <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" required>
                            <option value="">請選擇類別</option>
                            <option value="電子產品">電子產品</option>
                            <option value="電子配件">電子配件</option>
                            <option value="儲存裝置">儲存裝置</option>
                            <option value="辦公用品">辦公用品</option>
                            <option value="其他">其他</option>
                        </select>
                        <div class="invalid-feedback">請選擇類別</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">狀態</label>
                        <select class="form-select" id="status">
                            <option value="active">上架</option>
                            <option value="inactive">下架</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">儲存</button>
            </div>
        </div>
    </div>
</div>

<script>
let editMode = false;

$(document).ready(function() {
    loadProducts();
});

function loadProducts() {
    $.ajax({
        url: 'api/products.php',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                renderTable(response.data);
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入產品資料失敗', 'danger');
        }
    });
}

function renderTable(products) {
    let tbody = '';
    if (products.length === 0) {
        tbody = '<tr><td colspan="6" class="text-center">目前沒有產品資料</td></tr>';
    } else {
        products.forEach(function(prod) {
            const statusBadge = prod.status === 'active' 
                ? '<span class="badge bg-success">上架</span>'
                : '<span class="badge bg-secondary">下架</span>';
            tbody += `
                <tr>
                    <td>${prod.id}</td>
                    <td>${prod.name}</td>
                    <td>${formatCurrency(prod.price)}</td>
                    <td>${prod.category}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editProduct(${prod.id})">
                            <i class="bi bi-pencil"></i> 編輯
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${prod.id})">
                            <i class="bi bi-trash"></i> 刪除
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    $('#productTable tbody').html(tbody);
}

function openAddModal() {
    editMode = false;
    $('#modalTitle').text('新增產品');
    $('#productForm')[0].reset();
    $('#productId').val('');
    $('#productForm').removeClass('was-validated');
}

function editProduct(id) {
    editMode = true;
    $('#modalTitle').text('編輯產品');
    $('#productForm').removeClass('was-validated');
    
    $.ajax({
        url: `api/products.php?id=${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const prod = response.data;
                $('#productId').val(prod.id);
                $('#name').val(prod.name);
                $('#price').val(prod.price);
                $('#category').val(prod.category);
                $('#status').val(prod.status);
                
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入產品資料失敗', 'danger');
        }
    });
}

function saveProduct() {
    const form = document.getElementById('productForm');
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    const data = {
        name: $('#name').val(),
        price: parseFloat($('#price').val()),
        category: $('#category').val(),
        status: $('#status').val()
    };
    
    const id = $('#productId').val();
    const url = id ? `api/products.php?id=${id}` : 'api/products.php';
    const method = id ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                loadProducts();
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON || { message: '操作失敗' };
            showAlert(response.message, 'danger');
        }
    });
}

function deleteProduct(id) {
    confirmDelete(function() {
        $.ajax({
            url: `api/products.php?id=${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadProducts();
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || { message: '刪除失敗' };
                showAlert(response.message, 'danger');
            }
        });
    });
}
</script>
