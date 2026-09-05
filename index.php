<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');

// ============================================================
// VERIFICAÇÃO: vendor do Composer é obrigatório
// ============================================================
$vendor = __DIR__ . '/vendor/autoload.php';

if (!file_exists($vendor)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Dependencias do Composer nao encontradas!',
        'erro' => 'Arquivo vendor/autoload.php nao existe.',
        'solucao' => [
            '1' => 'Abra o terminal na pasta do projeto (C:\\biblioteca)',
            '2' => 'Execute: composer install',
            '3' => 'Se nao tiver o Composer, baixe em: https://getcomposer.org/download/',
            '4' => 'Depois de rodar composer install, recarregue a pagina'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

require $vendor;

// ============================================================
// AUTOLOAD PRÓPRIO — classes do projeto nas subpastas
// ============================================================
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $fileName = end($parts) . '.php';

    $searchDirs = [
        __DIR__ . '/Controllers',
        __DIR__ . '/DAO',
        __DIR__ . '/Database',
        __DIR__ . '/Http',
        __DIR__ . '/Middlewares',
        __DIR__ . '/Middlewares/Autor',
        __DIR__ . '/Middlewares/Categoria',
        __DIR__ . '/Middlewares/Emprestimo',
        __DIR__ . '/Middlewares/Livro',
        __DIR__ . '/Middlewares/Validation',
        __DIR__ . '/Models',
        __DIR__ . '/Routes',
        __DIR__ . '/Services',
    ];

    foreach ($searchDirs as $dir) {
        $path = $dir . '/' . $fileName;
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Api\Database\MysqlDatabase;
use Api\Server;

try {
    $config = require __DIR__ . '/config.php';

    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);

    $mysqlDatabase = new MysqlDatabase($config);

    $container = $builder->build();
    $container->set(MysqlDatabase::class, $mysqlDatabase);

    AppFactory::setContainer($container);
    $app = AppFactory::create();
    $container->set(\Slim\App::class, $app);

    $server = $container->get(Server::class);
    $server->run();
} catch (\Throwable $e) {
    $msg = $e->getMessage();
    if (strpos($msg, 'SQLSTATE[HY000] [2002]') !== false || strpos($msg, 'connect') !== false) {
        $msg = 'Nao foi possivel conectar ao MySQL. Verifique se o MySQL esta rodando e se os dados em config.php estao corretos. Rode /diagnostico.php para verificar.';
    }
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $msg,
        'dica' => 'Acesse http://localhost:8080/diagnostico.php para diagnosticar'
    ]);
}
