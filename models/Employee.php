<?php
require_once __DIR__ . '/../config/database.php';

/**
 * 員工模型
 * Employee Model
 */
class Employee {
    private $db;
    private $table = 'employees';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * 取得所有員工
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * 取得單一員工
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * 新增員工
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, department, phone, hire_date, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['department'],
            $data['phone'],
            $data['hire_date'],
            $data['status']
        ]);
    }
    
    /**
     * 更新員工
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, department = ?, phone = ?, hire_date = ?, status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['department'],
            $data['phone'],
            $data['hire_date'],
            $data['status'],
            $id
        ]);
    }
    
    /**
     * 刪除員工
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * 取得活躍員工（用於下拉選單）
     */
    public function getActive() {
        $stmt = $this->db->query("SELECT id, name FROM {$this->table} WHERE status = 'active' ORDER BY name");
        return $stmt->fetchAll();
    }
}
