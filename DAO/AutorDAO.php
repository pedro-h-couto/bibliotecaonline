<?php

namespace Api\DAO;

use Api\Models\Autor;
use Api\Database\MysqlDatabase;
use Exception;
use PDO;

class AutorDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        error_log("⬆️ AutorDAO::__construct()");
        $this->database = $databaseInstance;
    }

    public function create(Autor $autor): int
    {
        error_log("🟢 AutorDAO::create()");

        $sql = "
            INSERT INTO Autor
            (
                nome,
                nacionalidade
            )
            VALUES (?, ?)
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $autor->getNome(),
            $autor->getNacionalidade()
        ]);

        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir autor");
        }

        return (int)$insertId;
    }

    public function delete(Autor $autor): bool
    {
        error_log("🟢 AutorDAO::delete()");

        $sql = "
            DELETE FROM Autor
            WHERE idAutor = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $autor->getIdAutor()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function update(Autor $autor): bool
    {
        error_log("🟢 AutorDAO::update()");

        $sql = "
            UPDATE Autor
            SET
                nome = ?,
                nacionalidade = ?
            WHERE idAutor = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $autor->getNome(),
            $autor->getNacionalidade(),
            $autor->getIdAutor()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("🟢 AutorDAO::findAll()");

        $sql = "
            SELECT *
            FROM Autor
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($rows as $row) {

            $autor = new Autor();

            $autor->setIdAutor(
                (int)$row['idAutor']
            );

            $autor->setNome(
                $row['nome']
            );

            $autor->setNacionalidade(
                $row['nacionalidade']
            );

            $resultado[] = $autor;
        }

        return $resultado;
    }

    public function count(): int
    {
        error_log("🟢 AutorDAO::count()");

        $sql = "
            SELECT COUNT(*) AS qtd
            FROM Autor
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['qtd'];
    }

    public function findById(int $idAutor): ?Autor
    {
        error_log("🟢 AutorDAO::findById()");

        $resultado = $this->findByField(
            'idAutor',
            $idAutor
        );

        return $resultado[0] ?? null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("🟢 AutorDAO::findByField()");

        $allowedFields = [
            'idAutor',
            'nome',
            'nacionalidade'
        ];

        if (!in_array($field, $allowedFields)) {
            throw new Exception(
                "Campo inválido para busca"
            );
        }

        $sql = "
            SELECT *
            FROM Autor
            WHERE $field = ?
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $value
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($rows as $row) {

            $autor = new Autor();

            $autor->setIdAutor(
                (int)$row['idAutor']
            );

            $autor->setNome(
                $row['nome']
            );

            $autor->setNacionalidade(
                $row['nacionalidade']
            );

            $resultado[] = $autor;
        }

        return $resultado;
    }
}