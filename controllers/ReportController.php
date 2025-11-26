<?php
require_once __DIR__ . '/../models/Report.php';

/**
 * 報表控制器
 * Report Controller
 */
class ReportController {
    private $model;
    
    public function __construct() {
        $this->model = new Report();
    }
    
    /**
     * 處理 API 請求
     */
    public function handleRequest() {
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $type = $_GET['type'] ?? '';
        
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => '不支援的請求方法']);
            return;
        }
        
        try {
            switch ($type) {
                case 'employee_sales_total':
                    // 各員工銷售總量
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getEmployeeSalesTotal()
                    ]);
                    break;
                    
                case 'product_sales_total':
                    // 各產品銷售總量
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getProductSalesTotal()
                    ]);
                    break;
                    
                case 'employee_sales_avg':
                    // 各員工平均銷售數量
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getEmployeeSalesAverage()
                    ]);
                    break;
                    
                case 'product_sales_avg':
                    // 各產品平均銷售數量
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getProductSalesAverage()
                    ]);
                    break;
                    
                case 'sales_by_employee':
                    // 依員工名稱查銷售列表
                    $name = $_GET['name'] ?? '';
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getSalesByEmployeeName($name)
                    ]);
                    break;
                    
                case 'recommended_products':
                    // 必推商品
                    echo json_encode([
                        'success' => true, 
                        'data' => $this->model->getRecommendedProducts()
                    ]);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => '請指定報表類型']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
