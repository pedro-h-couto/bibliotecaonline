<?php

namespace Api\Models;
use JsonSerializable;

class Emprestimo implements JsonSerializable
{
    private ?int $idEmprestimo = null;

    private string $dataEmprestimo;
    private ?string $dataDevolucao = null;

    private string $status;

    private ?Livro $livro = null;
    private ?Leitor $leitor = null;

    // ID
    public function getIdEmprestimo(): ?int
    {
        return $this->idEmprestimo;
    }

    public function setIdEmprestimo(int $id): void
    {
        $this->idEmprestimo = $id;
    }

    // DATA EMPRESTIMO
    public function getDataEmprestimo(): string
    {
        return $this->dataEmprestimo;
    }

    public function setDataEmprestimo(string $data): void
    {
        $this->dataEmprestimo = $data;
    }

    // DATA DEVOLUÇÃO
    public function getDataDevolucao(): ?string
    {
        return $this->dataDevolucao;
    }

    public function setDataDevolucao(?string $data): void
    {
        $this->dataDevolucao = $data;
    }

    // STATUS (🔥 AQUI ESTÁ A CORREÇÃO)
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    // LIVRO
    public function getLivro(): ?Livro
    {
        return $this->livro;
    }

    public function setLivro(Livro $livro): void
    {
        $this->livro = $livro;
    }

    // LEITOR
    public function getLeitor(): ?Leitor
    {
        return $this->leitor;
    }

    public function setLeitor(Leitor $leitor): void
    {
        $this->leitor = $leitor;
    }

    public function jsonSerialize(): array
    {
        return [
            'idEmprestimo' => $this->getIdEmprestimo(),
            'dataEmprestimo' => $this->getDataEmprestimo(),
            'dataDevolucao' => $this->getDataDevolucao(),
            'status' => $this->getStatus(),
            'livro' => $this->getLivro(),
            'leitor' => $this->getLeitor()
        ];
    }
}
