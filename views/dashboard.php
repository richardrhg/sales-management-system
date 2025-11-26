<div class="row">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> 儀表板</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">員工管理</h4>
                        <p class="card-text">管理公司員工資料</p>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=employees" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">產品管理</h4>
                        <p class="card-text">管理公司產品資料</p>
                    </div>
                    <div>
                        <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=products" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">銷售管理</h4>
                        <p class="card-text">管理銷售紀錄</p>
                    </div>
                    <div>
                        <i class="bi bi-cart-check" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=sales" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5><i class="bi bi-star"></i> 必推商品</h5>
            </div>
            <div class="card-body">
                <div id="recommendedProducts">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">載入中...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5><i class="bi bi-trophy"></i> 銷售排行榜</h5>
            </div>
            <div class="card-body">
                <div id="salesRanking">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">載入中...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 載入必推商品
    $.get('api/reports.php?type=recommended_products', function(response) {
        if (response.success) {
            let html = '<ul class="list-group">';
            response.data.products.forEach(function(product, index) {
                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <span class="badge bg-primary rounded-pill me-2">${index + 1}</span>
                            ${product.name}
                        </span>
                        <span class="badge bg-success rounded-pill">銷售量: ${product.total_quantity}</span>
                    </li>
                `;
            });
            html += '</ul>';
            html += `<p class="text-muted mt-2 small">全產品平均銷售量: ${parseFloat(response.data.overall_avg).toFixed(2)}</p>`;
            $('#recommendedProducts').html(html);
        }
    });
    
    // 載入員工銷售排行
    $.get('api/reports.php?type=employee_sales_total', function(response) {
        if (response.success) {
            let html = '<ul class="list-group">';
            response.data.slice(0, 5).forEach(function(employee, index) {
                const badgeClass = index === 0 ? 'bg-warning' : (index === 1 ? 'bg-secondary' : 'bg-info');
                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <span class="badge ${badgeClass} rounded-pill me-2">${index + 1}</span>
                            ${employee.name} (${employee.department})
                        </span>
                        <span class="badge bg-primary rounded-pill">總銷售量: ${employee.total_quantity}</span>
                    </li>
                `;
            });
            html += '</ul>';
            $('#salesRanking').html(html);
        }
    });
});
</script>
