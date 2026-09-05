<?php

namespace Api\Models;

use Api\Models\Autor;
use Api\Models\Categoria;
use JsonSerializable;
use Exception;

class Livro implements JsonSerializable
{
    private int $idLivro;
    private Autor $autor;
    private Categoria $categoria;
    private string $titulo = "";
    private int $anoPublicacao;
    private int $quantidade;

    public function __construct()
    {
        $this->autor = new Autor();
        $this->categoria = new Categoria();
    }

    // ==========================
    // ID LIVRO
    // ==========================

    public function getIdLivro(): ?int
    {
        return $this->idLivro;
    }

    public function setIdLivro(int $valor): void
    {
        if ($valor <= 0) {
            throw new Exception(
                "idLivro deve ser um número inteiro positivo."
            );
        }

        $this->idLivro = $valor;
    }

    // ==========================
    // AUTOR
    // ==========================

    public function getAutor(): Autor
    {
        return $this->autor;
    }

    public function setAutor(Autor $autor): void
    {
        $this->autor = $autor;
    }

    // ==========================
    // CATEGORIA
    // ==========================

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    // ==========================
    // TÍTULO
    // ==========================

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $titulo = trim($titulo);

        if (strlen($titulo) < 3) {
            throw new Exception(
                "titulo deve possuir pelo menos 3 caracteres."
            );
        }

        if (strlen($titulo) > 150) {
            throw new Exception(
                "titulo deve possuir no máximo 150 caracteres."
            );
        }

        $this->titulo = $titulo;
    }

    // ==========================
    // ANO PUBLICAÇÃO
    // ==========================

    public function getAnoPublicacao(): int
    {
        return $this->anoPublicacao;
    }

    public function setAnoPublicacao(int $ano): void
    {
        if ($ano <= 0) {
            throw new Exception(
                "anoPublicacao deve ser maior que zero."
            );
        }

        $this->anoPublicacao = $ano;
    }

    // ==========================
    // QUANTIDADE
    // ==========================

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): void
    {
        if ($quantidade < 0) {
            throw new Exception(
                "quantidade não pode ser negativa."
            );
        }

        $this->quantidade = $quantidade;
    }

    // ==========================
    // JSON
    // ==========================

    public function jsonSerialize(): array
    {
        return [
            'idLivro' => $this->getIdLivro(),
            'titulo' => $this->getTitulo(),
            'anoPublicacao' => $this->getAnoPublicacao(),
            'quantidade' => $this->getQuantidade(),
            'autor' => $this->getAutor(),
            'categoria' => $this->getCategoria()
        ];
    }
}