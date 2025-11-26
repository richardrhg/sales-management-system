<?php
require_once __DIR__ . '/../config/database.php';

/**
 * 銷售模型
 * Sales Model
 */
class Sales {
    private $db;
    private $table = 'sales';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * 取得所有銷售紀錄（含員工和產品名稱）
     */
    public function getAll() {
        $sql = "SELECT s.*, e.name as employee_name, p.name as product_name, p.price 
                FROM {$this->table} s 
                LEFT JOIN employees e ON s.employee_id = e.id 
                LEFT JOIN products p ON s.product_id = p.id 
                ORDER BY s.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * 取得單一銷售紀錄
     */
    public function getById($id) {
        $sql = "SELECT s.*, e.name as employee_name, p.name as product_name, p.price 
                FROM {$this->table} s 
                LEFT JOIN employees e ON s.employee_id = e.id 
                LEFT JOIN products p ON s.product_id = p.id 
                WHERE s.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * 新增銷售紀錄
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (employee_id, product_id, quantity, sale_date) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['employee_id'],
            $data['product_id'],
            $data['quantity'],
            $data['sale_date']
        ]);
    }
    
    /**
     * 更新銷售紀錄
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET employee_id = ?, product_id = ?, quantity = ?, sale_date = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['employee_id'],
            $data['product_id'],
            $data['quantity'],
            $data['sale_date'],
            $id
        ]);
    }
    
    /**
     * 刪除銷售紀錄
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
