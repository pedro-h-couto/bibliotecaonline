<?php

namespace Api\Services;

use Api\Models\Categoria;
use Api\DAO\CategoriaDAO;
use Api\DAO\LivroDAO;
use Api\Http\ErrorResponse;
use Api\Database\MysqlDatabase;
use stdClass;

/**
 * Camada de regra de negócio da entidade Categoria.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class CategoriaService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var CategoriaDAO
     */
    private CategoriaDAO $categoriaDAO;

    /**
     * Injeção de dependência.
     *
     * @param CategoriaDAO $categoriaDAODependency
     */
    private LivroDAO $livroDAO;

    public function __construct(CategoriaDAO $categoriaDAODependency)
    {
        error_log("⬆️ CategoriaService::__construct()");
        $this->categoriaDAO = $categoriaDAODependency;
        $this->livroDAO = new LivroDAO(new MysqlDatabase());
    }

    /**
     * Cria uma nova categoria.
     *
     * Regras:
     * - Não permite nome duplicado.
     *
     * @param stdClass $objPHP
     * @return Categoria
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Categoria
    {
        error_log("🟣 CategoriaService::createService()");

        $categoria = new Categoria();
        $categoria->setNomeCategoria(
            $objPHP->categoria->nomeCategoria
        );

        $resultado = $this->categoriaDAO->findByField(
            'nomeCategoria',
            $categoria->getNomeCategoria()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Categoria já existe",
                [
                    "message" =>
                        "A categoria {$categoria->getNomeCategoria()} já existe"
                ]
            );
        }

        return $this->categoriaDAO->create($categoria);
    }

    /**
     * Retorna quantidade total de categorias.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 CategoriaService::countService()");
        return $this->categoriaDAO->count();
    }

    /**
     * Lista todas as categorias.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 CategoriaService::findAllService()");
        return $this->categoriaDAO->findAll();
    }

    /**
     * Busca categoria por ID.
     *
     * @param int $idCategoria
     * @return Categoria|null
     */
    public function findByIdService(int $idCategoria): ?Categoria
    {
        error_log("🟣 CategoriaService::findByIdService()");

        $categoria = new Categoria();
        $categoria->setIdCategoria($idCategoria);

        return $this->categoriaDAO->findById(
            $categoria->getIdCategoria()
        );
    }

    /**
     * Atualiza categoria existente.
     *
     * @param int $idCategoria
     * @param string $nomeCategoria
     * @return bool
     * @throws ErrorResponse
     */
    public function updateService(
        int $idCategoria,
        string $nomeCategoria
    ): bool {
        error_log("🟣 CategoriaService::updateService()");

        $categoriaExistente =
            $this->categoriaDAO->findById($idCategoria);

        if (!$categoriaExistente) {
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" =>
                        "Não existe categoria com id {$idCategoria}"
                ]
            );
        }

        $categoria = new Categoria();
        $categoria->setIdCategoria($idCategoria);
        $categoria->setNomeCategoria($nomeCategoria);

        return $this->categoriaDAO->update($categoria);
    }

    /**
     * Remove categoria existente.
     *
     * @param int $idCategoria
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $idCategoria): bool
    {
        error_log("🟣 CategoriaService::deleteService()");

        $categoriaExistente =
            $this->categoriaDAO->findById($idCategoria);

        if (!$categoriaExistente) {
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" =>
                        "Não existe categoria com id {$idCategoria}"
                ]
            );
        }

        $livros = $this->livroDAO->findByField('Categoria_idCategoria', $idCategoria);
        if (count($livros) > 0) {
            throw new ErrorResponse(
                400,
                "Nao e possivel excluir esta categoria",
                [
                    "message" =>
                        "Esta categoria possui " . count($livros) . " livro(s) cadastrado(s). Exclua os livros primeiro."
                ]
            );
        }

        $categoria = new Categoria();
        $categoria->setIdCategoria($idCategoria);

        return $this->categoriaDAO->delete($categoria);
    }
}