<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>員工銷售管理系統</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .content-area {
            padding: 20px;
        }
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- 導航列 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-shop"></i> 員工銷售管理系統
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=employees">
                            <i class="bi bi-people"></i> 員工管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=products">
                            <i class="bi bi-box-seam"></i> 產品管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=sales">
                            <i class="bi bi-cart-check"></i> 銷售管理
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bar-chart-line"></i> 查詢報表
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?page=reports&type=employee_sales_total">各員工銷售總量</a></li>
                            <li><a class="dropdown-item" href="index.php?page=reports&type=product_sales_total">各產品銷售總量</a></li>
                            <li><a class="dropdown-item" href="index.php?page=reports&type=employee_sales_avg">各員工平均銷售數量</a></li>
                            <li><a class="dropdown-item" href="index.php?page=reports&type=product_sales_avg">各產品平均銷售數量</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=reports&type=sales_by_employee">依員工名稱查詢</a></li>
                            <li><a class="dropdown-item" href="index.php?page=reports&type=recommended_products">必推商品</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 主要內容區 -->
    <div class="container-fluid">
        <div class="row">
            <main class="col-12 px-4 content-area">
                <!-- 警告訊息區 -->
                <div id="alertArea"></div>
                
                <?php
                $page = $_GET['page'] ?? 'dashboard';
                $viewPath = __DIR__ . "/../{$page}/index.php";
                
                if (file_exists($viewPath)) {
                    include $viewPath;
                } else {
                    include __DIR__ . "/../dashboard.php";
                }
                ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS & jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 共用 JS -->
    <script>
        // 顯示警告訊息
        function showAlert(message, type = 'success') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('#alertArea').html(alertHtml);
            setTimeout(() => {
                $('.alert').alert('close');
            }, 3000);
        }
        
        // 確認刪除對話框
        function confirmDelete(callback) {
            if (confirm('確定要刪除此項目嗎？此操作無法復原。')) {
                callback();
            }
        }
        
        // 格式化金額
        function formatCurrency(amount) {
            return new Intl.NumberFormat('zh-TW', {
                style: 'currency',
                currency: 'TWD',
                minimumFractionDigits: 0
            }).format(amount);
        }
        
        // 格式化日期
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('zh-TW');
        }
    </script>
</body>
</html>
