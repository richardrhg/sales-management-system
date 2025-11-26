<?php
require_once __DIR__ . '/../models/Sales.php';

/**
 * 銷售控制器
 * Sales Controller
 */
class SalesController {
    private $model;
    
    public function __construct() {
        $this->model = new Sales();
    }
    
    /**
     * 處理 API 請求
     */
    public function handleRequest() {
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? null;
        
        try {
            switch ($method) {
                case 'GET':
                    if ($id) {
                        $sale = $this->model->getById($id);
                        if ($sale) {
                            echo json_encode(['success' => true, 'data' => $sale]);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'message' => '找不到銷售紀錄']);
                        }
                    } else {
                        echo json_encode(['success' => true, 'data' => $this->model->getAll()]);
                    }
                    break;
                    
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->create($data)) {
                            echo json_encode(['success' => true, 'message' => '銷售紀錄新增成功']);
                        } else {
                            throw new Exception('銷售紀錄新增失敗');
                        }
                    }
                    break;
                    
                case 'PUT':
                    if (!$id) {
                        throw new Exception('缺少銷售紀錄 ID');
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($this->validateData($data)) {
                        if ($this->model->update($id, $data)) {
                            echo json_encode(['success' => true, 'message' => '銷售紀錄更新成功']);
                        } else {
                            throw new Exception('銷售紀錄更新失敗');
                        }
                    }
                    break;
                    
                case 'DELETE':
                    if (!$id) {
                        throw new Exception('缺少銷售紀錄 ID');
                    }
                    if ($this->model->delete($id)) {
                        echo json_encode(['success' => true, 'message' => '銷售紀錄刪除成功']);
                    } else {
                        throw new Exception('銷售紀錄刪除失敗');
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
        if (empty($data['employee_id'])) {
            throw new Exception('員工為必填欄位');
        }
        if (empty($data['product_id'])) {
            throw new Exception('產品為必填欄位');
        }
        if (!isset($data['quantity']) || $data['quantity'] <= 0) {
            throw new Exception('數量必須大於 0');
        }
        if (empty($data['sale_date'])) {
            throw new Exception('銷售日期為必填欄位');
        }
        return true;
    }
}
