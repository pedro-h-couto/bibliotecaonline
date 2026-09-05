<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'php_version' => PHP_VERSION,
    'vendor_exists' => file_exists(__DIR__ . '/../../vendor/autoload.php'),
    'time' => date('Y-m-d H:i:s')
]);
