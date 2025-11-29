-- 員工銷售管理系統資料庫初始化
-- Employee Sales Management System Database Initialization

-- 設定字符編碼
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS sales_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sales_management;

-- 確保使用 UTF-8 字符集
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- 員工資料表
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    hire_date DATE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 產品資料表
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 銷售紀錄資料表
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    sale_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 插入測試資料
INSERT INTO employees (name, department, phone, hire_date, status) VALUES
('王小明', '業務部', '0912-345-678', '2023-01-15', 'active'),
('李小華', '業務部', '0923-456-789', '2023-02-20', 'active'),
('張大偉', '行銷部', '0934-567-890', '2023-03-10', 'active'),
('陳美麗', '業務部', '0945-678-901', '2023-04-05', 'active'),
('林志成', '行銷部', '0956-789-012', '2023-05-18', 'inactive');

INSERT INTO products (name, price, category, status) VALUES
('筆記型電腦', 35000.00, '電子產品', 'active'),
('無線滑鼠', 800.00, '電子配件', 'active'),
('機械鍵盤', 2500.00, '電子配件', 'active'),
('27吋顯示器', 8500.00, '電子產品', 'active'),
('USB隨身碟 64GB', 350.00, '儲存裝置', 'active'),
('外接硬碟 1TB', 2000.00, '儲存裝置', 'inactive');

INSERT INTO sales (employee_id, product_id, quantity, sale_date) VALUES
(1, 1, 2, '2024-01-10'),
(1, 2, 5, '2024-01-12'),
(2, 3, 3, '2024-01-15'),
(2, 4, 1, '2024-01-18'),
(3, 5, 10, '2024-01-20'),
(4, 1, 1, '2024-01-22'),
(4, 2, 8, '2024-01-25'),
(1, 3, 4, '2024-02-01'),
(2, 5, 15, '2024-02-05'),
(3, 4, 2, '2024-02-10');
