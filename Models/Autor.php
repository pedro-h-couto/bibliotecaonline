<?php

namespace Api\Models;

use JsonSerializable;
use InvalidArgumentException;

class Autor implements JsonSerializable
{
    private int $idAutor;
    private string $nome = "";
    private string $nacionalidade = "";

    public function getIdAutor(): ?int
    {
        return $this->idAutor;
    }

    public function setIdAutor(int $idAutor): void
    {
        if ($idAutor <= 0) {
            throw new InvalidArgumentException(
                "idAutor deve ser maior que zero."
            );
        }

        $this->idAutor = $idAutor;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $nome = trim($nome);

        if ($nome === '') {
            throw new InvalidArgumentException(
                "Nome do autor não pode ser vazio."
            );
        }

        $this->nome = $nome;
    }

    public function getNacionalidade(): ?string
    {
        return $this->nacionalidade;
    }

    public function setNacionalidade(string $nacionalidade): void
    {
        $nacionalidade = trim($nacionalidade);

        if ($nacionalidade === '') {
            throw new InvalidArgumentException(
                "Nacionalidade não pode ser vazia."
            );
        }

        $this->nacionalidade = $nacionalidade;
    }

    public function jsonSerialize(): array
    {
        return [
            'idAutor' => $this->getIdAutor(),
            'nome' => $this->getNome(),
            'nacionalidade' => $this->getNacionalidade()
        ];
    }
}