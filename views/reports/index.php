<?php
$type = $_GET['type'] ?? '';
?>

<div class="row">
    <div class="col-12">
        <h2><i class="bi bi-bar-chart-line"></i> 查詢報表</h2>
        <hr>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="index.php?page=reports&type=employee_sales_total" class="btn btn-outline-primary <?php echo $type === 'employee_sales_total' ? 'active' : ''; ?>">
                各員工銷售總量
            </a>
            <a href="index.php?page=reports&type=product_sales_total" class="btn btn-outline-primary <?php echo $type === 'product_sales_total' ? 'active' : ''; ?>">
                各產品銷售總量
            </a>
            <a href="index.php?page=reports&type=employee_sales_avg" class="btn btn-outline-primary <?php echo $type === 'employee_sales_avg' ? 'active' : ''; ?>">
                各員工平均銷售
            </a>
            <a href="index.php?page=reports&type=product_sales_avg" class="btn btn-outline-primary <?php echo $type === 'product_sales_avg' ? 'active' : ''; ?>">
                各產品平均銷售
            </a>
            <a href="index.php?page=reports&type=sales_by_employee" class="btn btn-outline-primary <?php echo $type === 'sales_by_employee' ? 'active' : ''; ?>">
                依員工查詢
            </a>
            <a href="index.php?page=reports&type=recommended_products" class="btn btn-outline-primary <?php echo $type === 'recommended_products' ? 'active' : ''; ?>">
                必推商品
            </a>
        </div>
    </div>
</div>

<?php if ($type === 'employee_sales_total'): ?>
<!-- 各員工銷售總量 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="bi bi-people"></i> 各員工銷售總量</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>排名</th>
                                <th>員工姓名</th>
                                <th>部門</th>
                                <th>銷售總量</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
                            <tr>
                                <td colspan="4" class="text-center">載入中...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $.get('api/reports.php?type=employee_sales_total', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(row, index) {
                html += `
                    <tr>
                        <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                        <td>${row.name}</td>
                        <td>${row.department}</td>
                        <td><strong>${row.total_quantity}</strong></td>
                    </tr>
                `;
            });
            $('#reportTable').html(html || '<tr><td colspan="4" class="text-center">目前沒有資料</td></tr>');
        }
    });
});
</script>

<?php elseif ($type === 'product_sales_total'): ?>
<!-- 各產品銷售總量 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="bi bi-box-seam"></i> 各產品銷售總量</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>排名</th>
                                <th>產品名稱</th>
                                <th>類別</th>
                                <th>單價</th>
                                <th>銷售總量</th>
                                <th>總營收</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
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
<script>
$(document).ready(function() {
    $.get('api/reports.php?type=product_sales_total', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(row, index) {
                const revenue = (row.price || 0) * row.total_quantity;
                html += `
                    <tr>
                        <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                        <td>${row.name}</td>
                        <td>${row.category}</td>
                        <td>${formatCurrency(row.price)}</td>
                        <td><strong>${row.total_quantity}</strong></td>
                        <td class="text-success"><strong>${formatCurrency(revenue)}</strong></td>
                    </tr>
                `;
            });
            $('#reportTable').html(html || '<tr><td colspan="6" class="text-center">目前沒有資料</td></tr>');
        }
    });
});
</script>

<?php elseif ($type === 'employee_sales_avg'): ?>
<!-- 各員工平均銷售數量 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-graph-up"></i> 各員工平均銷售數量</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>排名</th>
                                <th>員工姓名</th>
                                <th>部門</th>
                                <th>銷售次數</th>
                                <th>平均銷售數量</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
                            <tr>
                                <td colspan="5" class="text-center">載入中...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $.get('api/reports.php?type=employee_sales_avg', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(row, index) {
                html += `
                    <tr>
                        <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                        <td>${row.name}</td>
                        <td>${row.department}</td>
                        <td>${row.sales_count}</td>
                        <td><strong>${parseFloat(row.avg_quantity).toFixed(2)}</strong></td>
                    </tr>
                `;
            });
            $('#reportTable').html(html || '<tr><td colspan="5" class="text-center">目前沒有資料</td></tr>');
        }
    });
});
</script>

<?php elseif ($type === 'product_sales_avg'): ?>
<!-- 各產品平均銷售數量 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h5><i class="bi bi-calculator"></i> 各產品平均銷售數量</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>排名</th>
                                <th>產品名稱</th>
                                <th>類別</th>
                                <th>單價</th>
                                <th>銷售次數</th>
                                <th>平均銷售數量</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
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
<script>
$(document).ready(function() {
    $.get('api/reports.php?type=product_sales_avg', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(row, index) {
                html += `
                    <tr>
                        <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                        <td>${row.name}</td>
                        <td>${row.category}</td>
                        <td>${formatCurrency(row.price)}</td>
                        <td>${row.sales_count}</td>
                        <td><strong>${parseFloat(row.avg_quantity).toFixed(2)}</strong></td>
                    </tr>
                `;
            });
            $('#reportTable').html(html || '<tr><td colspan="6" class="text-center">目前沒有資料</td></tr>');
        }
    });
});
</script>

<?php elseif ($type === 'sales_by_employee'): ?>
<!-- 依員工名稱查銷售列表 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5><i class="bi bi-search"></i> 依員工名稱查詢銷售紀錄</h5>
            </div>
            <div class="card-body">
                <form id="searchForm" class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="employeeName" placeholder="請輸入員工姓名" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> 查詢
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>銷售編號</th>
                                <th>員工姓名</th>
                                <th>部門</th>
                                <th>產品名稱</th>
                                <th>數量</th>
                                <th>單價</th>
                                <th>小計</th>
                                <th>銷售日期</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
                            <tr>
                                <td colspan="8" class="text-center text-muted">請輸入員工姓名進行查詢</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        const name = $('#employeeName').val().trim();
        if (!name) return;
        
        $.get('api/reports.php?type=sales_by_employee&name=' + encodeURIComponent(name), function(response) {
            if (response.success) {
                let html = '';
                if (response.data.length === 0) {
                    html = '<tr><td colspan="8" class="text-center text-warning">查無符合條件的銷售紀錄</td></tr>';
                } else {
                    response.data.forEach(function(row) {
                        const subtotal = row.price * row.quantity;
                        html += `
                            <tr>
                                <td>${row.id}</td>
                                <td>${row.employee_name}</td>
                                <td>${row.department}</td>
                                <td>${row.product_name}</td>
                                <td>${row.quantity}</td>
                                <td>${formatCurrency(row.price)}</td>
                                <td class="text-success">${formatCurrency(subtotal)}</td>
                                <td>${row.sale_date}</td>
                            </tr>
                        `;
                    });
                }
                $('#reportTable').html(html);
            }
        });
    });
});
</script>

<?php elseif ($type === 'recommended_products'): ?>
<!-- 必推商品 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5><i class="bi bi-star-fill"></i> 必推商品 (銷售量 > 全產品平均 或 Top 5)</h5>
            </div>
            <div class="card-body">
                <div id="avgInfo" class="alert alert-info">
                    載入中...
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>推薦排名</th>
                                <th>產品名稱</th>
                                <th>類別</th>
                                <th>單價</th>
                                <th>銷售總量</th>
                                <th>推薦理由</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable">
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
<script>
$(document).ready(function() {
    $.get('api/reports.php?type=recommended_products', function(response) {
        if (response.success) {
            const overallAvg = parseFloat(response.data.overall_avg).toFixed(2);
            $('#avgInfo').html(`<i class="bi bi-info-circle"></i> 全產品平均銷售量：<strong>${overallAvg}</strong>，以下為銷售量超過平均或 Top 5 的必推商品`);
            
            let html = '';
            response.data.products.forEach(function(row, index) {
                const reason = row.total_quantity > response.data.overall_avg 
                    ? '<span class="badge bg-success">超過平均</span>'
                    : '<span class="badge bg-primary">Top 5</span>';
                html += `
                    <tr>
                        <td>
                            <span class="badge bg-warning text-dark" style="font-size: 1.2rem;">
                                <i class="bi bi-trophy"></i> ${index + 1}
                            </span>
                        </td>
                        <td><strong>${row.name}</strong></td>
                        <td>${row.category}</td>
                        <td>${formatCurrency(row.price)}</td>
                        <td><span class="badge bg-danger" style="font-size: 1rem;">${row.total_quantity}</span></td>
                        <td>${reason}</td>
                    </tr>
                `;
            });
            $('#reportTable').html(html || '<tr><td colspan="6" class="text-center">目前沒有資料</td></tr>');
        }
    });
});
</script>

<?php else: ?>
<!-- 預設顯示 -->
<div class="row">
    <div class="col-12 text-center">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 請選擇上方的報表類型進行查詢
        </div>
    </div>
</div>
<?php endif; ?>
