<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Representa a entidade Categoria da biblioteca.
 *
 * Objetivo:
 * - Encapsular os dados de uma categoria.
 * - Garantir integridade dos atributos via getters e setters.
 */
class Categoria implements JsonSerializable
{
    /**
     * Identificador único da categoria.
     *
     * @var int
     */
    private int $idCategoria;

    /**
     * Nome da categoria.
     *
     * @var string
     */
    private string $nomeCategoria = "";

    public function __construct()
    {
        // error_log("⬆️ Categoria::__construct()");
    }

    /**
     * Retorna o ID da categoria.
     *
     * @return int|null
     */
    public function getIdCategoria(): ?int
    {
        return $this->idCategoria;
    }

    /**
     * Define o ID da categoria.
     *
     * @param int $value
     * @return void
     */
    public function setIdCategoria(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(
                "idCategoria deve ser maior que zero."
            );
        }

        $this->idCategoria = $value;
    }

    /**
     * Retorna o nome da categoria.
     *
     * @return string|null
     */
    public function getNomeCategoria(): ?string
    {
        return $this->nomeCategoria;
    }

    /**
     * Define o nome da categoria.
     *
     * @param string $value
     * @return void
     */
    public function setNomeCategoria(string $value): void
    {
        $nome = trim($value);

        if ($nome === '') {
            throw new InvalidArgumentException(
                "nomeCategoria não pode ser vazio."
            );
        }

        if (mb_strlen($nome) > 50) {
            throw new InvalidArgumentException(
                "nomeCategoria deve possuir no máximo 50 caracteres."
            );
        }

        $this->nomeCategoria = $nome;
    }

    /**
     * Converte objeto para JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'idCategoria' => $this->getIdCategoria(),
            'nomeCategoria' => $this->getNomeCategoria()
        ];
    }
}