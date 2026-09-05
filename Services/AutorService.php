<?php

namespace Api\Services;

use Api\DAO\AutorDAO;
use Api\DAO\LivroDAO;
use Api\Models\Autor;
use Api\Database\MysqlDatabase;
use Api\Http\ErrorResponse;

class AutorService
{
    private AutorDAO $dao;
    private LivroDAO $livroDAO;

    public function __construct()
    {
        $this->dao = new AutorDAO(new MysqlDatabase());
        $this->livroDAO = new LivroDAO(new MysqlDatabase());
    }

    public function createService(object $data): Autor
    {
        $autor = new Autor();
        $autor->setNome($data->nome);
        $autor->setNacionalidade($data->nacionalidade);

        $id = $this->dao->create($autor);
        $autor->setIdAutor($id);

        return $autor;
    }

    public function findAllService(): array
    {
        return $this->dao->findAll();
    }

    public function findByIdService(int $id): ?Autor
    {
        return $this->dao->findById($id);
    }

    public function updateService(int $id, object $data): Autor
    {
        $autor = $this->dao->findById($id);

        $autor->setNome($data->nome);
        $autor->setNacionalidade($data->nacionalidade);

        $this->dao->update($autor);

        return $autor;
    }

    public function deleteService(int $id): bool
    {
        $autor = $this->dao->findById($id);

        if (!$autor) {
            throw new ErrorResponse(404, "Autor nao encontrado");
        }

        $livros = $this->livroDAO->findByField('Autor_idAutor', $id);
        if (count($livros) > 0) {
            throw new ErrorResponse(
                400,
                "Nao e possivel excluir este autor",
                ["message" => "Este autor possui " . count($livros) . " livro(s) cadastrado(s). Exclua os livros primeiro."]
            );
        }

        return $this->dao->delete($autor);
    }
}