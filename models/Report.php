<?php
require_once __DIR__ . '/../config/database.php';

/**
 * 報表模型
 * Report Model
 */
class Report {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * 各員工銷售總量（SUM + GROUP BY）
     */
    public function getEmployeeSalesTotal() {
        $sql = "SELECT e.id, e.name, e.department, COALESCE(SUM(s.quantity), 0) as total_quantity
                FROM employees e
                LEFT JOIN sales s ON e.id = s.employee_id
                GROUP BY e.id, e.name, e.department
                ORDER BY total_quantity DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * 各產品銷售總量（SUM + GROUP BY）
     */
    public function getProductSalesTotal() {
        $sql = "SELECT p.id, p.name, p.category, p.price, COALESCE(SUM(s.quantity), 0) as total_quantity
                FROM products p
                LEFT JOIN sales s ON p.id = s.product_id
                GROUP BY p.id, p.name, p.category, p.price
                ORDER BY total_quantity DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * 各員工平均銷售數量（AVG）
     */
    public function getEmployeeSalesAverage() {
        $sql = "SELECT e.id, e.name, e.department, 
                COALESCE(AVG(s.quantity), 0) as avg_quantity,
                COUNT(s.id) as sales_count
                FROM employees e
                LEFT JOIN sales s ON e.id = s.employee_id
                GROUP BY e.id, e.name, e.department
                ORDER BY avg_quantity DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * 各產品平均銷售數量（AVG）
     */
    public function getProductSalesAverage() {
        $sql = "SELECT p.id, p.name, p.category, p.price,
                COALESCE(AVG(s.quantity), 0) as avg_quantity,
                COUNT(s.id) as sales_count
                FROM products p
                LEFT JOIN sales s ON p.id = s.product_id
                GROUP BY p.id, p.name, p.category, p.price
                ORDER BY avg_quantity DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * 依員工名稱查銷售列表
     */
    public function getSalesByEmployeeName($name) {
        $sql = "SELECT s.*, e.name as employee_name, e.department, p.name as product_name, p.price
                FROM sales s
                JOIN employees e ON s.employee_id = e.id
                JOIN products p ON s.product_id = p.id
                WHERE e.name LIKE ?
                ORDER BY s.sale_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll();
    }
    
    /**
     * 必推商品（銷售量 > 全產品平均，或 Top 5）
     */
    public function getRecommendedProducts() {
        // 取得全產品平均銷售量
        $avgSql = "SELECT AVG(total_qty) as overall_avg FROM (
                    SELECT COALESCE(SUM(s.quantity), 0) as total_qty
                    FROM products p
                    LEFT JOIN sales s ON p.id = s.product_id
                    WHERE p.status = 'active'
                    GROUP BY p.id
                   ) as product_totals";
        $avgStmt = $this->db->query($avgSql);
        $overallAvg = $avgStmt->fetch()['overall_avg'] ?? 0;
        
        // 取得銷售量超過平均的產品（Top 5）
        $sql = "SELECT p.id, p.name, p.category, p.price, 
                COALESCE(SUM(s.quantity), 0) as total_quantity,
                ? as overall_avg
                FROM products p
                LEFT JOIN sales s ON p.id = s.product_id
                WHERE p.status = 'active'
                GROUP BY p.id, p.name, p.category, p.price
                HAVING total_quantity > ?
                ORDER BY total_quantity DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$overallAvg, $overallAvg]);
        $recommended = $stmt->fetchAll();
        
        // 如果沒有超過平均的，就取 Top 5
        if (empty($recommended)) {
            $sql = "SELECT p.id, p.name, p.category, p.price, 
                    COALESCE(SUM(s.quantity), 0) as total_quantity,
                    ? as overall_avg
                    FROM products p
                    LEFT JOIN sales s ON p.id = s.product_id
                    WHERE p.status = 'active'
                    GROUP BY p.id, p.name, p.category, p.price
                    ORDER BY total_quantity DESC
                    LIMIT 5";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$overallAvg]);
            $recommended = $stmt->fetchAll();
        }
        
        return [
            'products' => $recommended,
            'overall_avg' => $overallAvg
        ];
    }
}
