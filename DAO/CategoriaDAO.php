<?php

namespace Api\DAO;

use Api\Models\Categoria;
use Api\Database\MysqlDatabase;
use Exception;

class CategoriaDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;

        error_log("⬆️ CategoriaDAO::__construct()");
    }

    public function create(Categoria $objCategoria): Categoria
    {
        error_log("🟢 CategoriaDAO::create()");

        $sql = "
            INSERT INTO Categoria (nomeCategoria)
            VALUES (:nomeCategoria)
        ";

        $parametros = [
            ':nomeCategoria' => $objCategoria->getNomeCategoria()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar categoria.");
        }

        $novoID = (int) $this->database->getConnection()->lastInsertId();

        $objCategoria->setIdCategoria($novoID);

        return $objCategoria;
    }

    public function delete(Categoria $objCategoria): bool
    {
        error_log("🟢 CategoriaDAO::delete()");

        $sql = "
            DELETE FROM Categoria
            WHERE idCategoria = :idCategoria
        ";

        $parametros = [
            ':idCategoria' => $objCategoria->getIdCategoria()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function update(Categoria $objCategoria): bool
    {
        error_log("🟢 CategoriaDAO::update()");

        $sql = "
            UPDATE Categoria
            SET nomeCategoria = :nomeCategoria
            WHERE idCategoria = :idCategoria
        ";

        $parametros = [
            ':nomeCategoria' => $objCategoria->getNomeCategoria(),
            ':idCategoria' => $objCategoria->getIdCategoria()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("🟢 CategoriaDAO::findAll()");

        $sql = "SELECT * FROM Categoria";

        $stmt = $this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $categorias = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $categoria = new Categoria();

            $categoria->setIdCategoria(
                (int) $linhaMatriz['idCategoria']
            );

            $categoria->setNomeCategoria(
                $linhaMatriz['nomeCategoria']
            );

            $categorias[] = $categoria;
        }

        return $categorias;
    }

    public function count(): int
    {
        error_log("🟢 CategoriaDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM Categoria";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    public function findById(int $idCategoria): ?Categoria
    {
        error_log("🟢 CategoriaDAO::findById()");

        $resultado = $this->findByField(
            'idCategoria',
            $idCategoria
        );

        if (!empty($resultado)) {
            return $resultado[0];
        }

        return null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("🟢 CategoriaDAO::findByField()");

        $camposPermitidos = [
            'idCategoria',
            'nomeCategoria'
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM Categoria WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([
            ':value' => $value
        ]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $categorias = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $categoria = new Categoria();

            $categoria->setIdCategoria(
                (int) $linhaMatriz['idCategoria']
            );

            $categoria->setNomeCategoria(
                $linhaMatriz['nomeCategoria']
            );

            $categorias[] = $categoria;
        }

        return $categorias;
    }
}