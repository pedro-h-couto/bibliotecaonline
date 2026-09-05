<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$resultados = [];
$configs = [
    ['host' => '127.0.0.1', 'port' => 3306'user' => 'root', 'password' => '', 'database' => 'biblioteca'],
    ['host' => 'localhost', 'port' => 3306, 'user' => 'root', 'password' => '', 'database' => 'biblioteca'],
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'password' => 'root', 'database' => 'biblioteca'],
    ['host' => '127.0.0.1', 'port' => 3307, 'user' => 'root', 'password' => '', 'database' => 'biblioteca'],
    ['host' => '127.0.0.1', 'port' => 3308, 'user' => 'root', 'password' => '', 'database' => 'biblioteca'],
];

foreach ($configs as $cfg) {
    try {
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset=utf8mb4";
        $pdo = new PDO($dsn, $cfg['user'], $cfg['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2
        ]);
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $resultados[] = [
            'config' => $cfg,
            'status' => 'SUCESSO',
            'tabelas' => $tables
        ];
    } catch (PDOException $e) {
        $resultados[] = [
            'config' => $cfg,
            'status' => 'FALHA',
            'erro' => $e->getMessage()
        ];
    }
}

$mysqlRunning = false;
foreach ([3306, 3307, 3308] as $porta) {
    $conn = @fsockopen('127.0.0.1', $porta, $errno, $errstr, 1);
    if ($conn) { fclose($conn); $mysqlRunning = true; break; }
}

echo json_encode([
    'diagnostico' => 'MySQL',
    'mysql_detectado' => $mysqlRunning,
    'php_version' => PHP_VERSION,
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'testes' => $resultados,
    'instrucoes' => [
        '1. Se todos falharam, o MySQL NAO esta rodando.',
        '2. XAMPP/WAMP: clique Start no MySQL.',
        '3. MySQL 8: a senha do root pode ser diferente.',
        '4. Porta diferente: edite config.php.',
        '5. Sem tabelas: importe banco.sql no phpMyAdmin.'
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
