<?php
// 設定錯誤報告（開發環境）
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 設定字符編碼
header('Content-Type: application/json; charset=UTF-8');

try {
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../controllers/EmployeeController.php';
    
    $controller = new EmployeeController();
    $controller->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '伺服器錯誤: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_UNESCAPED_UNICODE);
}
