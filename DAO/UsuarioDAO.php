<?php

namespace Api\DAO;

use Api\Models\Usuario;
use Api\Database\MysqlDatabase;
use PDO;

class UsuarioDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
    }

    public function findByEmail(string $email): ?Usuario
    {
        $sql = "SELECT * FROM Usuario WHERE email = ?";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $usuario = new Usuario();
        $usuario->setIdUsuario((int)$row['idUsuario']);
        $usuario->setNome($row['nome']);
        $usuario->setEmail($row['email']);
        $usuario->setSenha($row['senha']);
        return $usuario;
    }
}
