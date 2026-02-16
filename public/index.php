<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Set log directory
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
ini_set('error_log', $logDir . '/error.log');

// Load environment (optional) and start router
require_once __DIR__ . '/../config/env.php';

if (getenv('APP_ENV') === 'development') {
    ini_set('display_errors', '1');
}

header("Content-Type: application/json; charset=utf-8");

try {
    require_once __DIR__ . '/../routes.php';
} catch (Exception $e) {
    error_log('Application error: ' . $e->getMessage());
    Response::json(["error" => "Internal Server Error"], 500);
}
