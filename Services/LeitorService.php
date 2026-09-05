<?php

namespace Api\Services;

use Api\DAO\LeitorDAO;
use Api\DAO\EmprestimoDAO;
use Api\Models\Leitor;
use Api\Database\MysqlDatabase;
use Api\Http\ErrorResponse;

class LeitorService
{
    private LeitorDAO $dao;
    private EmprestimoDAO $emprestimoDAO;

    public function __construct()
    {
        $this->dao = new LeitorDAO(new MysqlDatabase());
        $this->emprestimoDAO = new EmprestimoDAO(new MysqlDatabase());
    }

    public function createService(object $data): Leitor
    {
        $leitor = new Leitor();

        $leitor->setNome($data->nome);
        $leitor->setEmail($data->email);
        $leitor->setTelefone($data->telefone);

        $id = $this->dao->create($leitor);
        $leitor->setIdLeitor($id);

        return $leitor;
    }

    public function findAllService(): array
    {
        return $this->dao->findAll();
    }

    public function findByIdService(int $id): ?Leitor
    {
        return $this->dao->findById($id);
    }

    public function deleteService(int $id): bool
    {
        $leitor = $this->dao->findById($id);

        if (!$leitor) {
            throw new ErrorResponse(404, "Leitor nao encontrado");
        }

        $emprestimos = $this->emprestimoDAO->findByField('Leitor_idLeitor', $id);
        if (count($emprestimos) > 0) {
            throw new ErrorResponse(
                400,
                "Nao e possivel excluir este leitor",
                ["message" => "Este leitor possui " . count($emprestimos) . " emprestimo(s) cadastrado(s). Exclua os emprestimos primeiro."]
            );
        }

        return $this->dao->delete($leitor);
    }

    public function countService(): int
    {
        return $this->dao->count();
    }

    public function updateService(int $id, object $data): Leitor
    {
        $leitor = $this->dao->findById($id);

        $leitor->setNome($data->nome);
        $leitor->setEmail($data->email);
        $leitor->setTelefone($data->telefone);

        $this->dao->update($leitor);

        return $leitor;
    }
}