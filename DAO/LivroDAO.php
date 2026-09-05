<?php

namespace Api\DAO;

use Api\Models\Livro;
use Api\Models\Autor;
use Api\Models\Categoria;
use Api\Database\MysqlDatabase;
use Exception;
use PDO;

class LivroDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        error_log("⬆️ LivroDAO::__construct()");
        $this->database = $databaseInstance;
    }

    public function create(Livro $livro): int
    {
        error_log("🟢 LivroDAO::create()");

        $sql = "
            INSERT INTO Livro
            (
                titulo,
                anoPublicacao,
                quantidade,
                Autor_idAutor,
                Categoria_idCategoria
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $params = [
            $livro->getTitulo(),
            $livro->getAnoPublicacao(),
            $livro->getQuantidade(),
            $livro->getAutor()->getIdAutor(),
            $livro->getCategoria()->getIdCategoria()
        ];

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir livro");
        }

        return (int)$insertId;
    }

    public function delete(Livro $livro): bool
    {
        error_log("🟢 LivroDAO::delete()");

        $sql = "DELETE FROM Livro WHERE idLivro = ?";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $livro->getIdLivro()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function update(Livro $livro): bool
    {
        error_log("🟢 LivroDAO::update()");

        $sql = "
            UPDATE Livro
            SET
                titulo = ?,
                anoPublicacao = ?,
                quantidade = ?,
                Autor_idAutor = ?,
                Categoria_idCategoria = ?
            WHERE idLivro = ?
        ";

        $params = [
            $livro->getTitulo(),
            $livro->getAnoPublicacao(),
            $livro->getQuantidade(),
            $livro->getAutor()->getIdAutor(),
            $livro->getCategoria()->getIdCategoria(),
            $livro->getIdLivro()
        ];

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("🟢 LivroDAO::findAll()");

        $sql = "
            SELECT
                l.idLivro,
                l.titulo,
                l.anoPublicacao,
                l.quantidade,

                a.idAutor,
                a.nome,
                a.nacionalidade,

                c.idCategoria,
                c.nomeCategoria

            FROM Livro l

            INNER JOIN Autor a
                ON l.Autor_idAutor = a.idAutor

            INNER JOIN Categoria c
                ON l.Categoria_idCategoria = c.idCategoria
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($rows as $row) {

            $autor = new Autor();
            $autor->setIdAutor((int)$row['idAutor']);
            $autor->setNome($row['nome']);
            $autor->setNacionalidade($row['nacionalidade']);

            $categoria = new Categoria();
            $categoria->setIdCategoria((int)$row['idCategoria']);
            $categoria->setNomeCategoria($row['nomeCategoria']);

            $livro = new Livro();
            $livro->setIdLivro((int)$row['idLivro']);
            $livro->setTitulo($row['titulo']);
            $livro->setAnoPublicacao((int)$row['anoPublicacao']);
            $livro->setQuantidade((int)$row['quantidade']);

            $livro->setAutor($autor);
            $livro->setCategoria($categoria);

            $resultado[] = $livro;
        }

        return $resultado;
    }

    public function count(): int
    {
        error_log("🟢 LivroDAO::count()");

        $sql = "
            SELECT COUNT(*) AS qtd
            FROM Livro
        ";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['qtd'];
    }

    public function findById(int $idLivro): ?Livro
    {
        error_log("🟢 LivroDAO::findById()");

        $resultado = $this->findByField(
            'idLivro',
            $idLivro
        );

        return $resultado[0] ?? null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("🟢 LivroDAO::findByField()");

        $allowedFields = [
            'idLivro',
            'titulo',
            'anoPublicacao',
            'quantidade',
            'Autor_idAutor',
            'Categoria_idCategoria'
        ];

        if (!in_array($field, $allowedFields)) {
            throw new Exception("Campo inválido para busca");
        }

        $sql = "
            SELECT *
            FROM Livro
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
                (int)$row['Autor_idAutor']
            );

            $categoria = new Categoria();
            $categoria->setIdCategoria(
                (int)$row['Categoria_idCategoria']
            );

            $livro = new Livro();

            $livro->setIdLivro(
                (int)$row['idLivro']
            );

            $livro->setTitulo(
                $row['titulo']
            );

            $livro->setAnoPublicacao(
                (int)$row['anoPublicacao']
            );

            $livro->setQuantidade(
                (int)$row['quantidade']
            );

            $livro->setAutor($autor);
            $livro->setCategoria($categoria);

            $resultado[] = $livro;
        }

        return $resultado;
    }
}