<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-people"></i> 員工管理</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="openAddModal()">
                <i class="bi bi-plus-lg"></i> 新增員工
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
                    <table class="table table-striped table-hover" id="employeeTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>部門</th>
                                <th>電話</th>
                                <th>到職日期</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">載入中...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 員工表單 Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">新增員工</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="employeeForm" novalidate>
                    <input type="hidden" id="employeeId">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">姓名 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback">請輸入姓名</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="department" class="form-label">部門 <span class="text-danger">*</span></label>
                        <select class="form-select" id="department" required>
                            <option value="">請選擇部門</option>
                            <option value="業務部">業務部</option>
                            <option value="行銷部">行銷部</option>
                            <option value="研發部">研發部</option>
                            <option value="人資部">人資部</option>
                            <option value="財務部">財務部</option>
                        </select>
                        <div class="invalid-feedback">請選擇部門</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">電話</label>
                        <input type="tel" class="form-control" id="phone" pattern="[0-9\-]{8,15}">
                        <div class="invalid-feedback">請輸入有效的電話號碼</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hire_date" class="form-label">到職日期 <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="hire_date" required>
                        <div class="invalid-feedback">請選擇到職日期</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">狀態</label>
                        <select class="form-select" id="status">
                            <option value="active">在職</option>
                            <option value="inactive">離職</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="saveEmployee()">儲存</button>
            </div>
        </div>
    </div>
</div>

<script>
let editMode = false;

$(document).ready(function() {
    loadEmployees();
});

function loadEmployees() {
    $.ajax({
        url: 'api/employees.php',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                renderTable(response.data);
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入員工資料失敗', 'danger');
        }
    });
}

function renderTable(employees) {
    let tbody = '';
    if (employees.length === 0) {
        tbody = '<tr><td colspan="7" class="text-center">目前沒有員工資料</td></tr>';
    } else {
        employees.forEach(function(emp) {
            const statusBadge = emp.status === 'active' 
                ? '<span class="badge bg-success">在職</span>'
                : '<span class="badge bg-secondary">離職</span>';
            tbody += `
                <tr>
                    <td>${emp.id}</td>
                    <td>${emp.name}</td>
                    <td>${emp.department}</td>
                    <td>${emp.phone || '-'}</td>
                    <td>${emp.hire_date}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editEmployee(${emp.id})">
                            <i class="bi bi-pencil"></i> 編輯
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteEmployee(${emp.id})">
                            <i class="bi bi-trash"></i> 刪除
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    $('#employeeTable tbody').html(tbody);
}

function openAddModal() {
    editMode = false;
    $('#modalTitle').text('新增員工');
    $('#employeeForm')[0].reset();
    $('#employeeId').val('');
    $('#employeeForm').removeClass('was-validated');
}

function editEmployee(id) {
    editMode = true;
    $('#modalTitle').text('編輯員工');
    $('#employeeForm').removeClass('was-validated');
    
    $.ajax({
        url: `api/employees.php?id=${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const emp = response.data;
                $('#employeeId').val(emp.id);
                $('#name').val(emp.name);
                $('#department').val(emp.department);
                $('#phone').val(emp.phone);
                $('#hire_date').val(emp.hire_date);
                $('#status').val(emp.status);
                
                const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
                modal.show();
            } else {
                showAlert(response.message, 'danger');
            }
        },
        error: function() {
            showAlert('載入員工資料失敗', 'danger');
        }
    });
}

function saveEmployee() {
    const form = document.getElementById('employeeForm');
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    const data = {
        name: $('#name').val(),
        department: $('#department').val(),
        phone: $('#phone').val(),
        hire_date: $('#hire_date').val(),
        status: $('#status').val()
    };
    
    const id = $('#employeeId').val();
    const url = id ? `api/employees.php?id=${id}` : 'api/employees.php';
    const method = id ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
                loadEmployees();
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

function deleteEmployee(id) {
    confirmDelete(function() {
        $.ajax({
            url: `api/employees.php?id=${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadEmployees();
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
