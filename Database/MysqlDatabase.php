<?php

namespace Api\Database;

use PDO;
use PDOException;
use Exception;

/**
 * Classe responsável por gerenciar a conexão com o banco MySQL.
 *
 * - Suporta passagem de dados de conexão via construtor.
 * - Mantém um singleton PDO para reutilização de conexão.
 */
class MysqlDatabase
{
    /** @var PDO|null Pool de conexão singleton */
    private static ?PDO $connection = null;

    /** @var string Configurações do banco */
    private string $host;
    private string $user;
    private string $password;
    private string $database;
    private int $port;

    /**
     * Construtor recebe dados de conexão.
     *
     * @param array $config
     *
     * Exemplo:
     * [
     *   'host' => '127.0.0.1',
     *   'user' => 'root',
     *   'password' => '',
     *   'database' => 'biblioteca',
     *   'port' => 3306
     * ]
     */
    public function __construct(array $config = [])
    {
        $this->host = $config['host'] ?? '127.0.0.1';
        $this->user = $config['user'] ?? 'root';
        $this->password = $config['password'] ?? '';
        $this->database = $config['database'] ?? 'biblioteca';
        $this->port = $config['port'] ?? 3306;
    }

    public function getConfig(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'user' => $this->user,
            'database' => $this->database
        ];
    }

    /**
     * Retorna a conexão PDO singleton.
     *
     * @return PDO
     * @throws Exception
     */
    public function getConnection(): PDO
    {
        if (self::$connection === null) {

            try {

                $dsn =
                    "mysql:host={$this->host};" .
                    "port={$this->port};" .
                    "dbname={$this->database};" .
                    "charset=utf8mb4";

                self::$connection = new PDO(
                    $dsn,
                    $this->user,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => true
                    ]
                );

                error_log("⬆️ Conectado ao banco Biblioteca com sucesso!");

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                error_log("❌ Falha ao conectar ao MySQL: " . $erro);
                $dica = '';
                if (strpos($erro, '2002') !== false || strpos($erro, 'recusou') !== false) {
                    $dica = ' Dica: o servico MySQL pode estar parado. Inicie-o no XAMPP/WAMP ou verifique a porta ' . $this->port;
                } elseif (strpos($erro, '1045') !== false) {
                    $dica = ' Dica: senha incorreta. Verifique config.php.';
                } elseif (strpos($erro, '1049') !== false) {
                    $dica = ' Dica: banco "' . $this->database . '" nao existe. Importe o banco.sql no phpMyAdmin.';
                }
                throw new Exception("Falha ao conectar ao MySQL: " . $erro . $dica);
            }
        }

        return self::$connection;
    }
}