<?php

namespace Api\DAO;

use Api\Models\Emprestimo;
use Api\Models\Autor;
use Api\Models\Categoria;
use Api\Models\Livro;
use Api\Models\Leitor;
use Api\Database\MysqlDatabase;
use PDO;
use Exception;

class EmprestimoDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
    }

    public function create(Emprestimo $emp): int
    {
        $sql = "
            INSERT INTO Emprestimo
            (dataEmprestimo, dataDevolucao, status, Livro_idLivro, Leitor_idLeitor)
            VALUES (?, ?, ?, ?, ?)
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $emp->getDataEmprestimo(),
            $emp->getDataDevolucao(),
            $emp->getStatus(),
            $emp->getLivro()->getIdLivro(),
            $emp->getLeitor()->getIdLeitor()
        ]);

        $id = $pdo->lastInsertId();
        if (!$id) {
            throw new Exception("Erro ao criar emprestimo");
        }
        return (int)$id;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT
                e.idEmprestimo, e.dataEmprestimo, e.dataDevolucao, e.status,
                l.idLivro, l.titulo, l.anoPublicacao, l.quantidade,
                l.Autor_idAutor, l.Categoria_idCategoria,
                le.idLeitor, le.nome, le.email, le.telefone
            FROM Emprestimo e
            INNER JOIN Livro l ON e.Livro_idLivro = l.idLivro
            INNER JOIN Leitor le ON e.Leitor_idLeitor = le.idLeitor
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($rows as $row) {
            $autor = new Autor();
            $autor->setIdAutor((int)$row['Autor_idAutor']);

            $categoria = new Categoria();
            $categoria->setIdCategoria((int)$row['Categoria_idCategoria']);

            $livro = new Livro();
            $livro->setIdLivro((int)$row['idLivro']);
            $livro->setTitulo($row['titulo']);
            $livro->setAnoPublicacao((int)$row['anoPublicacao']);
            $livro->setQuantidade((int)$row['quantidade']);
            $livro->setAutor($autor);
            $livro->setCategoria($categoria);

            $leitor = new Leitor();
            $leitor->setIdLeitor((int)$row['idLeitor']);
            $leitor->setNome($row['nome']);
            $leitor->setEmail($row['email'] ?? '');
            $leitor->setTelefone($row['telefone'] ?? '');

            $emp = new Emprestimo();
            $emp->setIdEmprestimo((int)$row['idEmprestimo']);
            $emp->setDataEmprestimo($row['dataEmprestimo']);
            $emp->setDataDevolucao($row['dataDevolucao']);
            $emp->setStatus($row['status']);
            $emp->setLivro($livro);
            $emp->setLeitor($leitor);
            $result[] = $emp;
        }
        return $result;
    }

    public function findById(int $id): ?Emprestimo
    {
        $sql = "SELECT * FROM Emprestimo WHERE idEmprestimo = ?";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $emp = new Emprestimo();
        $emp->setIdEmprestimo((int)$row['idEmprestimo']);
        $emp->setDataEmprestimo($row['dataEmprestimo']);
        $emp->setDataDevolucao($row['dataDevolucao']);
        $emp->setStatus($row['status']);
        return $emp;
    }

    public function update(Emprestimo $emp): bool
    {
        $sql = "
            UPDATE Emprestimo
            SET dataEmprestimo = ?, dataDevolucao = ?, status = ?, Livro_idLivro = ?, Leitor_idLeitor = ?
            WHERE idEmprestimo = ?
        ";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $emp->getDataEmprestimo(),
            $emp->getDataDevolucao(),
            $emp->getStatus(),
            $emp->getLivro()->getIdLivro(),
            $emp->getLeitor()->getIdLeitor(),
            $emp->getIdEmprestimo()
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(Emprestimo $emp): bool
    {
        $sql = "DELETE FROM Emprestimo WHERE idEmprestimo = ?";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$emp->getIdEmprestimo()]);
        return $stmt->rowCount() > 0;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM Emprestimo";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function findByField(string $field, $value): array
    {
        $allowedFields = ['idEmprestimo', 'Livro_idLivro', 'Leitor_idLeitor', 'status'];
        if (!in_array($field, $allowedFields)) {
            throw new Exception("Campo invalido para busca");
        }

        $sql = "SELECT * FROM Emprestimo WHERE $field = ?";
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $emp = new Emprestimo();
            $emp->setIdEmprestimo((int)$row['idEmprestimo']);
            $emp->setDataEmprestimo($row['dataEmprestimo']);
            $emp->setDataDevolucao($row['dataDevolucao']);
            $emp->setStatus($row['status']);
            $result[] = $emp;
        }
        return $result;
    }
}
