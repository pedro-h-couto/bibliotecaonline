<?php

namespace Api\DAO;

use Api\Models\Leitor;
use Api\Database\MysqlDatabase;
use Exception;
use PDO;

class LeitorDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
    }

    public function create(Leitor $leitor): int
    {
        $sql = "
            INSERT INTO Leitor
            (
                nome,
                email,
                telefone
            )
            VALUES (?, ?, ?)
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $leitor->getNome(),
            $leitor->getEmail(),
            $leitor->getTelefone()
        ]);

        $id = $pdo->lastInsertId();

        if (!$id) {
            throw new Exception("Falha ao inserir leitor");
        }

        return (int)$id;
    }

    public function update(Leitor $leitor): bool
    {
        $sql = "
            UPDATE Leitor
            SET
                nome = ?,
                email = ?,
                telefone = ?
            WHERE idLeitor = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $leitor->getNome(),
            $leitor->getEmail(),
            $leitor->getTelefone(),
            $leitor->getIdLeitor()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(Leitor $leitor): bool
    {
        $sql = "
            DELETE FROM Leitor
            WHERE idLeitor = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $leitor->getIdLeitor()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT *
            FROM Leitor
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($rows as $row) {

            $leitor = new Leitor();

            $leitor->setIdLeitor(
                (int)$row['idLeitor']
            );

            $leitor->setNome(
                $row['nome']
            );

            $leitor->setEmail(
                $row['email']
            );

            $leitor->setTelefone(
                $row['telefone']
            );

            $resultado[] = $leitor;
        }

        return $resultado;
    }

    public function findById(int $id): ?Leitor
    {
        $sql = "
            SELECT *
            FROM Leitor
            WHERE idLeitor = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $leitor = new Leitor();

        $leitor->setIdLeitor(
            (int)$row['idLeitor']
        );

        $leitor->setNome(
            $row['nome']
        );

        $leitor->setEmail(
            $row['email']
        );

        $leitor->setTelefone(
            $row['telefone']
        );

        return $leitor;
    }

    public function count(): int
    {
        $sql = "
            SELECT COUNT(*) AS qtd
            FROM Leitor
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['qtd'];
    }
}