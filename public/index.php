<?php
/**
 * 員工銷售管理系統 - 主入口
 * Employee Sales Management System - Main Entry Point
 */

// 錯誤報告設定
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 設定時區
date_default_timezone_set('Asia/Taipei');

// 載入主佈局
require_once __DIR__ . '/../views/layouts/main.php';
