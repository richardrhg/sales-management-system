<?php
/**
 * 員工銷售管理系統 - 主入口
 * Employee Sales Management System - Main Entry Point
 */

// 設定字符編碼
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// 錯誤報告設定
error_reporting(E_ALL);
// 開發環境顯示錯誤，生產環境應設為 0
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// 設定時區
date_default_timezone_set('Asia/Taipei');

// 載入主佈局
try {
    require_once __DIR__ . '/../views/layouts/main.php';
} catch (Exception $e) {
    echo '<h1>錯誤</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
