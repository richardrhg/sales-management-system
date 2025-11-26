<?php
require_once __DIR__ . '/../config/database.php';

/**
 * 產品模型
 * Product Model
 */
class Product {
    private $db;
    private $table = 'products';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * 取得所有產品
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * 取得單一產品
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * 新增產品
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, price, category, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['category'],
            $data['status']
        ]);
    }
    
    /**
     * 更新產品
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, price = ?, category = ?, status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['category'],
            $data['status'],
            $id
        ]);
    }
    
    /**
     * 刪除產品
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * 取得活躍產品（用於下拉選單）
     */
    public function getActive() {
        $stmt = $this->db->query("SELECT id, name, price FROM {$this->table} WHERE status = 'active' ORDER BY name");
        return $stmt->fetchAll();
    }
}
