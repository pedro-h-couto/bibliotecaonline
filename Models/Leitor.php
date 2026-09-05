<?php

namespace Api\Models;

use JsonSerializable;
use InvalidArgumentException;

class Leitor implements JsonSerializable
{
    private int $idLeitor;
    private string $nome = "";
    private string $telefone = "";
    private string $email = "";

    public function getIdLeitor(): ?int
    {
        return $this->idLeitor;
    }

    public function setIdLeitor(int $valor): void
    {
        if ($valor <= 0) {
            throw new InvalidArgumentException(
                "idLeitor deve ser maior que zero."
            );
        }

        $this->idLeitor = $valor;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $nome = trim($nome);

        if (strlen($nome) < 3) {
            throw new InvalidArgumentException(
                "nome deve possuir pelo menos 3 caracteres."
            );
        }

        if (strlen($nome) > 100) {
            throw new InvalidArgumentException(
                "nome deve possuir no máximo 100 caracteres."
            );
        }

        $this->nome = $nome;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): void
    {
        $this->telefone = trim($telefone);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "Email inválido."
            );
        }

        $this->email = $email;
    }

    public function jsonSerialize(): array
    {
        return [
            'idLeitor' => $this->getIdLeitor(),
            'nome' => $this->getNome(),
            'telefone' => $this->getTelefone(),
            'email' => $this->getEmail()
        ];
    }
}