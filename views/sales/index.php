<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-cart-check"></i> 銷售管理</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saleModal" onclick="openAddModal()">
                <i class="bi bi-plus-lg"></i> 新增銷售紀錄
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
                    <table class="table table-striped table-hover" id="saleTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>員工</th>
                                <th>產品</th>
                                <th>數量</th>
                                <th>單價</th>
                                <th>小計</th>
                                <th>銷售日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center">載入中...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 銷售表單 Modal -->
<div class="modal fade" id="saleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">新增銷售紀錄</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="saleForm" novalidate>
                    <input type="hidden" id="saleId">
                    
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">員工 <span class="text-danger">*</span></label>
                        <select class="form-select" id="employee_id" required>
                            <option value="">請選擇員工</option>
                        </select>
                        <div class="invalid-feedback">請選擇員工</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_id" class="form-label">產品 <span class="text-danger">*</span></label>
                        <select class="form-select" id="product_id" required>
                            <option value="">請選擇產品</option>
                        </select>
                        <div class="invalid-feedback">請選擇產品</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">數量 <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity" min="1" required>
                        <div class="invalid-feedback">數量必須大於 0</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sale_date" class="form-label">銷售日期 <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="sale_date" required>
                        <div class="invalid-feedback">請選擇銷售日期</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="saveSale()">儲存</button>
            </div>
        </div>
    </div>
</div>

<script>
let editMode = false;
let employees = [];
let products = [];

$(document).ready(function() {
    // 載入所有資料
    loadEmployees();
    loadProducts();
    loadSales();
    
    // 設定預設日期為今天
    $('#sale_date').val(new Date().toISOString().split('T')[0]);
});

function loadEmployees() {
    $.ajax({
        url: 'api/employees.php?action=active',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                employees = response.data;
                let options = '<option value="">請選擇員工</option>';
                employees.forEach(function(emp) {
                    options += `<option value="${emp.id}">${emp.name}</option>`;
                });
                $('#employee_id').html(options);
            }
        }
    });
}

function loadProducts() {
    $.ajax({
        url: 'api/products.php?action=active',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                products = response.data;
                let options = '<option value="">請選擇產品</option>';
                products.forEach(function(prod) {
                    options += `<option value="${prod.id}">${prod.name} (${formatCurrency(prod.price)})</option>`;
                });
                $('#product_id').html(options);
            }
        }
    });
}

function loadSales() {
    $.ajax({
        url: 'api/sales.php',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                renderTable(response.data);
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入銷售資料失敗', 'danger');
        }
    });
}

function renderTable(sales) {
    let tbody = '';
    if (sales.length === 0) {
        tbody = '<tr><td colspan="8" class="text-center">目前沒有銷售紀錄</td></tr>';
    } else {
        sales.forEach(function(sale) {
                const subtotal = (sale.price || 0) * sale.quantity;
            tbody += `
                <tr>
                    <td>${sale.id}</td>
                    <td>${sale.employee_name || '-'}</td>
                    <td>${sale.product_name || '-'}</td>
                    <td>${sale.quantity}</td>
                    <td>${formatCurrency(sale.price)}</td>
                    <td>${formatCurrency(subtotal)}</td>
                    <td>${sale.sale_date}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editSale(${sale.id})">
                            <i class="bi bi-pencil"></i> 編輯
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSale(${sale.id})">
                            <i class="bi bi-trash"></i> 刪除
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    $('#saleTable tbody').html(tbody);
}

function openAddModal() {
    editMode = false;
    $('#modalTitle').text('新增銷售紀錄');
    $('#saleForm')[0].reset();
    $('#saleId').val('');
    $('#sale_date').val(new Date().toISOString().split('T')[0]);
    $('#saleForm').removeClass('was-validated');
}

function editSale(id) {
    editMode = true;
    $('#modalTitle').text('編輯銷售紀錄');
    $('#saleForm').removeClass('was-validated');
    
    $.ajax({
        url: `api/sales.php?id=${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const sale = response.data;
                $('#saleId').val(sale.id);
                $('#employee_id').val(sale.employee_id);
                $('#product_id').val(sale.product_id);
                $('#quantity').val(sale.quantity);
                $('#sale_date').val(sale.sale_date);
                
                const modal = new bootstrap.Modal(document.getElementById('saleModal'));
                modal.show();
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入銷售資料失敗', 'danger');
        }
    });
}

function saveSale() {
    const form = document.getElementById('saleForm');
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    const data = {
        employee_id: parseInt($('#employee_id').val()),
        product_id: parseInt($('#product_id').val()),
        quantity: parseInt($('#quantity').val()),
        sale_date: $('#sale_date').val()
    };
    
    const id = $('#saleId').val();
    const url = id ? `api/sales.php?id=${id}` : 'api/sales.php';
    const method = id ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('saleModal')).hide();
                loadSales();
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

function deleteSale(id) {
    confirmDelete(function() {
        $.ajax({
            url: `api/sales.php?id=${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadSales();
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
