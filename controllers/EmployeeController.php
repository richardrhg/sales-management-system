<?php
require_once __DIR__ . '/../models/Employee.php';

/**
 * 員工控制器
 * Employee Controller
 */
class EmployeeController {
    private $model;
    
    public function __construct() {
        $this->model = new Employee();
    }
    
    /**
     * 處理 API 請求
     */
    public function handleRequest() {
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';
        $id = $_GET['id'] ?? null;
        
        try {
            switch ($method) {
                case 'GET':
                    if ($action === 'active') {
                        echo json_encode(['success' => true, 'data' => $this->model->getActive()]);
                    } elseif ($id) {
                        $employee = $this->model->getById($id);
                        if ($employee) {
                            echo json_encode(['success' => true, 'data' => $employee]);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'message' => '找不到員工']);
                        }
                    } else {
                        echo json_encode(['success' => true, 'data' => $this->model->getAll()]);
                    }
                    break;
                    
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->create($data)) {
                            echo json_encode(['success' => true, 'message' => '員工新增成功']);
                        } else {
                            throw new Exception('員工新增失敗');
                        }
                    }
                    break;
                    
                case 'PUT':
                    if (!$id) {
                        throw new Exception('缺少員工 ID');
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->update($id, $data)) {
                            echo json_encode(['success' => true, 'message' => '員工更新成功']);
                        } else {
                            throw new Exception('員工更新失敗');
                        }
                    }
                    break;
                    
                case 'DELETE':
                    if (!$id) {
                        throw new Exception('缺少員工 ID');
                    }
                    if ($this->model->delete($id)) {
                        echo json_encode(['success' => true, 'message' => '員工刪除成功']);
                    } else {
                        throw new Exception('員工刪除失敗');
                    }
                    break;
                    
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => '不支援的請求方法']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * 驗證資料
     */
    private function validateData($data) {
        if (empty($data['name'])) {
            throw new Exception('姓名為必填欄位');
        }
        if (empty($data['department'])) {
            throw new Exception('部門為必填欄位');
        }
        if (empty($data['hire_date'])) {
            throw new Exception('到職日期為必填欄位');
        }
        return true;
    }
}
