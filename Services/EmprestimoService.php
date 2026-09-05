<?php

namespace Api\Services;

use Api\DAO\EmprestimoDAO;
use Api\DAO\LivroDAO;
use Api\DAO\LeitorDAO;
use Api\Models\Emprestimo;
use Api\Models\Livro;
use Api\Models\Leitor;
use Api\Database\MysqlDatabase;
use Api\Http\ErrorResponse;

class EmprestimoService
{
    private EmprestimoDAO $dao;
    private LivroDAO $livroDAO;
    private LeitorDAO $leitorDAO;

    public function __construct(
        EmprestimoDAO $emprestimoDAO = null,
        LivroDAO $livroDAO = null,
        LeitorDAO $leitorDAO = null
    ) {
        $this->dao = $emprestimoDAO ?? new EmprestimoDAO(new MysqlDatabase());
        $this->livroDAO = $livroDAO ?? new LivroDAO(new MysqlDatabase());
        $this->leitorDAO = $leitorDAO ?? new LeitorDAO(new MysqlDatabase());
    }

    public function createService(object $data): Emprestimo
    {
        $livro = $this->livroDAO->findById((int)$data->idLivro);
        if (!$livro) {
            throw new ErrorResponse(404, "Livro nao encontrado", ["message" => "Nao existe livro com id {$data->idLivro}"]);
        }

        $leitor = $this->leitorDAO->findById((int)$data->idLeitor);
        if (!$leitor) {
            throw new ErrorResponse(404, "Leitor nao encontrado", ["message" => "Nao existe leitor com id {$data->idLeitor}"]);
        }

        $emp = new Emprestimo();
        $emp->setDataEmprestimo($data->dataEmprestimo);
        $emp->setDataDevolucao($data->dataDevolucao ?? null);
        $emp->setStatus($data->status);
        $emp->setLivro($livro);
        $emp->setLeitor($leitor);

        $id = $this->dao->create($emp);
        $emp->setIdEmprestimo($id);

        return $emp;
    }

    public function findAllService(): array
    {
        return $this->dao->findAll();
    }

    public function findByIdService(int $id): ?Emprestimo
    {
        return $this->dao->findById($id);
    }

    public function updateService(int $id, object $data): Emprestimo
    {
        $existente = $this->dao->findById($id);
        if (!$existente) {
            throw new ErrorResponse(404, "Emprestimo nao encontrado");
        }

        $livro = $this->livroDAO->findById((int)$data->idLivro);
        if (!$livro) {
            throw new ErrorResponse(404, "Livro nao encontrado", ["message" => "Nao existe livro com id {$data->idLivro}"]);
        }

        $leitor = $this->leitorDAO->findById((int)$data->idLeitor);
        if (!$leitor) {
            throw new ErrorResponse(404, "Leitor nao encontrado", ["message" => "Nao existe leitor com id {$data->idLeitor}"]);
        }

        $emp = new Emprestimo();
        $emp->setIdEmprestimo($id);
        $emp->setDataEmprestimo($data->dataEmprestimo);
        $emp->setDataDevolucao($data->dataDevolucao ?? null);
        $emp->setStatus($data->status);
        $emp->setLivro($livro);
        $emp->setLeitor($leitor);

        $this->dao->update($emp);
        return $emp;
    }

    public function deleteService(int $id): bool
    {
        $emp = $this->dao->findById($id);
        if (!$emp) {
            throw new ErrorResponse(404, "Emprestimo nao encontrado");
        }
        return $this->dao->delete($emp);
    }

    public function countService(): int
    {
        return $this->dao->count();
    }
}
