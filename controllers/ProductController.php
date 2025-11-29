<?php
require_once __DIR__ . '/../models/Product.php';

/**
 * 產品控制器
 * Product Controller
 */
class ProductController {
    private $model;
    
    public function __construct() {
        $this->model = new Product();
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
                        echo json_encode(['success' => true, 'data' => $this->model->getActive()], JSON_UNESCAPED_UNICODE);
                    } elseif ($id) {
                        $product = $this->model->getById($id);
                        if ($product) {
                            echo json_encode(['success' => true, 'data' => $product], JSON_UNESCAPED_UNICODE);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'message' => '找不到產品'], JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        $data = $this->model->getAll();
                        echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
                    }
                    break;
                    
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->create($data)) {
                            echo json_encode(['success' => true, 'message' => '產品新增成功']);
                        } else {
                            throw new Exception('產品新增失敗');
                        }
                    }
                    break;
                    
                case 'PUT':
                    if (!$id) {
                        throw new Exception('缺少產品 ID');
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->update($id, $data)) {
                            echo json_encode(['success' => true, 'message' => '產品更新成功']);
                        } else {
                            throw new Exception('產品更新失敗');
                        }
                    }
                    break;
                    
                case 'DELETE':
                    if (!$id) {
                        throw new Exception('缺少產品 ID');
                    }
                    if ($this->model->delete($id)) {
                        echo json_encode(['success' => true, 'message' => '產品刪除成功']);
                    } else {
                        throw new Exception('產品刪除失敗');
                    }
                    break;
                    
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => '不支援的請求方法']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    /**
     * 驗證資料
     */
    private function validateData($data) {
        if (empty($data['name'])) {
            throw new Exception('產品名稱為必填欄位');
        }
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            throw new Exception('價格為必填欄位且必須為大於等於 0 的有效數字');
        }
        if (empty($data['category'])) {
            throw new Exception('類別為必填欄位');
        }
        return true;
    }
}
