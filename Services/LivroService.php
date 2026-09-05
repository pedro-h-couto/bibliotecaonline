<?php

namespace Api\Services;

use Api\DAO\LivroDAO;
use Api\DAO\AutorDAO;
use Api\DAO\CategoriaDAO;
use Api\DAO\EmprestimoDAO;
use Api\Database\MysqlDatabase;
use Api\Models\Livro;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Livro.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class LivroService
{
    private LivroDAO $livroDAO;
    private AutorDAO $autorDAO;
    private CategoriaDAO $categoriaDAO;

    private EmprestimoDAO $emprestimoDAO;

    public function __construct(
        LivroDAO $livroDAODependency,
        AutorDAO $autorDAODependency,
        CategoriaDAO $categoriaDAODependency
    ) {
        error_log("⬆️ LivroService::__construct()");

        $this->livroDAO = $livroDAODependency;
        $this->autorDAO = $autorDAODependency;
        $this->categoriaDAO = $categoriaDAODependency;
        $this->emprestimoDAO = new EmprestimoDAO(new MysqlDatabase());
    }

    /**
     * Cria novo livro.
     */
    public function createService(stdClass $jsonLivro): Livro
    {
        error_log("🟣 LivroService::createService()");

        $autor = $this->autorDAO->findById(
            $jsonLivro->livro->autor->idAutor
        );

        if (!$autor) {
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" =>
                        "Não existe autor com id {$jsonLivro->livro->autor->idAutor}"
                ]
            );
        }

        $categoria = $this->categoriaDAO->findById(
            $jsonLivro->livro->categoria->idCategoria
        );

        if (!$categoria) {
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" =>
                        "Não existe categoria com id {$jsonLivro->livro->categoria->idCategoria}"
                ]
            );
        }

        $livro = new Livro();
        $livro->setTitulo(
            $jsonLivro->livro->titulo
        );
        $livro->setAnoPublicacao(
            $jsonLivro->livro->anoPublicacao
        );
        $livro->setQuantidade(
            $jsonLivro->livro->quantidade
        );
        $livro->setAutor($autor);
        $livro->setCategoria($categoria);

        $idCriado = $this->livroDAO->create(
            $livro
        );

        $livro->setIdLivro($idCriado);

        return $livro;
    }

    /**
     * Lista todos os livros.
     */
    public function findAllService(): array
    {
        error_log("🟣 LivroService::findAllService()");
        return $this->livroDAO->findAll();
    }

    /**
     * Busca livro por ID.
     */
    public function findByIdService(
        int $idLivro
    ): Livro {
        error_log("🟣 LivroService::findByIdService()");

        $livro = $this->livroDAO->findById(
            $idLivro
        );

        if (!$livro) {
            throw new ErrorResponse(
                404,
                "Livro não encontrado",
                [
                    "message" =>
                        "Não existe livro com id {$idLivro}"
                ]
            );
        }

        return $livro;
    }

    /**
     * Atualiza livro.
     */
    public function updateService(
        int $idLivro,
        array $requestBody
    ): bool {
        error_log("🟣 LivroService::updateService()");

        $livroExiste =
            $this->livroDAO->findById($idLivro);

        if (!$livroExiste) {
            throw new ErrorResponse(
                404,
                "Livro não encontrado",
                [
                    "message" =>
                        "Não existe livro com id {$idLivro}"
                ]
            );
        }

        $jsonLivro = $requestBody['livro'];

        $autor = $this->autorDAO->findById(
            $jsonLivro['autor']['idAutor']
        );

        if (!$autor) {
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" =>
                        "Autor informado não existe"
                ]
            );
        }

        $categoria = $this->categoriaDAO->findById(
            $jsonLivro['categoria']['idCategoria']
        );

        if (!$categoria) {
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" =>
                        "Categoria informada não existe"
                ]
            );
        }

        $livro = new Livro();
        $livro->setIdLivro($idLivro);
        $livro->setTitulo(
            $jsonLivro['titulo']
        );
        $livro->setAnoPublicacao(
            $jsonLivro['anoPublicacao']
        );
        $livro->setQuantidade(
            $jsonLivro['quantidade']
        );
        $livro->setAutor($autor);
        $livro->setCategoria($categoria);

        return $this->livroDAO->update(
            $livro
        );
    }

    /**
     * Remove livro.
     */
    public function deleteService(
        int $idLivro
    ): bool {
        error_log("🟣 LivroService::deleteService()");

        $livroExiste =
            $this->livroDAO->findById(
                $idLivro
            );

        if (!$livroExiste) {
            throw new ErrorResponse(
                404,
                "Livro não encontrado",
                [
                    "message" =>
                        "Não existe livro com id {$idLivro}"
                ]
            );
        }

        $emprestimos = $this->emprestimoDAO->findByField('Livro_idLivro', $idLivro);
        if (count($emprestimos) > 0) {
            throw new ErrorResponse(
                400,
                "Nao e possivel excluir este livro",
                [
                    "message" =>
                        "Este livro possui " . count($emprestimos) . " emprestimo(s) cadastrado(s). Exclua os emprestimos primeiro."
                ]
            );
        }

        $livro = new Livro();
        $livro->setIdLivro($idLivro);

        return $this->livroDAO->delete(
            $livro
        );
    }

    /**
     * Retorna quantidade total de livros.
     */
    public function countService(): int
    {
        error_log("🟣 LivroService::countService()");
        return $this->livroDAO->count();
    }
}