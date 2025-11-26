# 員工銷售管理系統 (Sales Management System)

資料庫專題實作 - 員工銷售管理系統

## 功能特色

### 員工管理 (CRUD)
- 新增、編輯、刪除員工資料
- 員工資料包含：姓名、部門、電話、到職日期、狀態

### 產品管理 (CRUD)
- 新增、編輯、刪除產品資料
- 產品資料包含：名稱、價格、類別、狀態

### 銷售管理 (CRUD)
- 新增、編輯、刪除銷售紀錄
- 銷售紀錄包含：員工、產品、數量、銷售日期

### 查詢報表
- 各員工銷售總量（SUM + GROUP BY）
- 各產品銷售總量
- 各員工平均銷售數量（AVG）
- 各產品平均銷售數量
- 依員工名稱查銷售列表
- 必推商品（銷售量 > 全產品平均，或 Top 5）

## 技術架構

- **前端**：HTML5、Bootstrap 5（繁體中文 UI）、jQuery + AJAX
- **後端**：PHP（簡易 MVC 結構）
- **資料庫**：MySQL + PDO
- **部署**：Docker（可部署至 Azure Web App for Containers）

## 專案結構

```
sales-management-system/
├── config/
│   ├── database.php      # 資料庫配置
│   └── init.sql          # 資料庫初始化 SQL
├── controllers/
│   ├── EmployeeController.php
│   ├── ProductController.php
│   ├── SalesController.php
│   └── ReportController.php
├── models/
│   ├── Employee.php
│   ├── Product.php
│   ├── Sales.php
│   └── Report.php
├── views/
│   ├── layouts/
│   │   └── main.php      # 主佈局
│   ├── dashboard.php     # 儀表板
│   ├── employees/
│   │   └── index.php
│   ├── products/
│   │   └── index.php
│   ├── sales/
│   │   └── index.php
│   └── reports/
│       └── index.php
├── public/
│   ├── api/              # API 端點
│   │   ├── employees.php
│   │   ├── products.php
│   │   ├── sales.php
│   │   └── reports.php
│   ├── css/
│   ├── js/
│   └── index.php         # 主入口
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 資料庫設計

### employees 表
| 欄位 | 類型 | 說明 |
|------|------|------|
| id | INT | 主鍵，自動遞增 |
| name | VARCHAR(100) | 姓名 |
| department | VARCHAR(100) | 部門 |
| phone | VARCHAR(20) | 電話 |
| hire_date | DATE | 到職日期 |
| status | ENUM | 狀態（active/inactive） |

### products 表
| 欄位 | 類型 | 說明 |
|------|------|------|
| id | INT | 主鍵，自動遞增 |
| name | VARCHAR(200) | 產品名稱 |
| price | DECIMAL(10,2) | 價格 |
| category | VARCHAR(100) | 類別 |
| status | ENUM | 狀態（active/inactive） |

### sales 表
| 欄位 | 類型 | 說明 |
|------|------|------|
| id | INT | 主鍵，自動遞增 |
| employee_id | INT | 員工 ID（外鍵） |
| product_id | INT | 產品 ID（外鍵） |
| quantity | INT | 數量 |
| sale_date | DATE | 銷售日期 |

## 本地開發

### 使用 Docker Compose

```bash
# 啟動所有服務
docker-compose up -d

# 查看服務狀態
docker-compose ps

# 停止服務
docker-compose down
```

啟動後可透過以下網址存取：
- 應用程式：http://localhost:8080
- phpMyAdmin：http://localhost:8081

### 環境變數

| 變數 | 預設值 | 說明 |
|------|--------|------|
| DB_HOST | localhost | 資料庫主機 |
| DB_NAME | sales_management | 資料庫名稱 |
| DB_USER | root | 資料庫使用者 |
| DB_PASS | (空) | 資料庫密碼 |

## Azure 部署

### 建立 Docker 映像

```bash
docker build -t sales-management-system .
```

### 推送至 Azure Container Registry

```bash
# 登入 Azure Container Registry
az acr login --name <registry-name>

# 標記映像
docker tag sales-management-system <registry-name>.azurecr.io/sales-management-system:latest

# 推送映像
docker push <registry-name>.azurecr.io/sales-management-system:latest
```

### 在 Azure Web App 設定環境變數

在 Azure Portal 的 Web App 設定中，新增以下應用程式設定：
- `DB_HOST`：Azure MySQL 伺服器主機名稱
- `DB_NAME`：資料庫名稱
- `DB_USER`：資料庫使用者
- `DB_PASS`：資料庫密碼

## 授權

本專案為學術專題實作使用。
