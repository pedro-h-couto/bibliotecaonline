<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$vendorPaths = [
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php',
];

$found = null;
foreach ($vendorPaths as $path) {
    if (file_exists($path)) {
        $found = $path;
        break;
    }
}

if (!$found) {
    echo json_encode([
        'status' => 'error',
        'message' => 'vendor/autoload.php nao encontrado em nenhum local!',
        'tentativas' => $vendorPaths,
        'dica' => 'Rode "composer install" na pasta que contem o composer.json'
    ]);
    exit;
}

try {
    require $found;
    echo json_encode([
        'status' => 'ok',
        'autoload' => 'success',
        'caminho' => $found,
        'php_version' => PHP_VERSION
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
