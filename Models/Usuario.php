<?php

namespace Api\Models;

use JsonSerializable;

class Usuario implements JsonSerializable
{
    private int $idUsuario;
    private string $nome = "";
    private string $email = "";
    private string $senha = "";

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario ?? null;
    }

    public function setIdUsuario(int $id): void
    {
        $this->idUsuario = $id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = trim($nome);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function jsonSerialize(): array
    {
        return [
            'idUsuario' => $this->getIdUsuario(),
            'nome' => $this->getNome(),
            'email' => $this->getEmail()
        ];
    }
}
