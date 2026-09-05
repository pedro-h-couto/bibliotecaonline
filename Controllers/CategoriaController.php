<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\CategoriaService;

/**
 * Classe CategoriaController
 *
 * Responsável pelos endpoints REST da entidade Categoria.
 */
class CategoriaController
{
    /**
     * Serviço da entidade Categoria.
     *
     * @var CategoriaService
     */
    private CategoriaService $categoriaService;

    /**
     * Injeção de dependência.
     *
     * @param CategoriaService $categoriaServiceDependency
     */
    public function __construct(CategoriaService $categoriaServiceDependency)
    {
        error_log("⬆️ CategoriaController::__construct()");
        $this->categoriaService = $categoriaServiceDependency;
    }

    /**
     * Cria nova categoria.
     *
     * Endpoint:
     * POST /api/v1/categorias
     */
    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $novaCategoria = $this->categoriaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'categorias' => [
                    [
                        'idCategoria' => $novaCategoria->getIdCategoria(),
                        'nomeCategoria' => $novaCategoria->getNomeCategoria()
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * Lista todas as categorias.
     *
     * Endpoint:
     * GET /api/v1/categorias
     */
    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::findAllController()");

        $categorias = $this->categoriaService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'categorias' => $categorias
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Busca categoria por ID.
     *
     * Endpoint:
     * GET /api/v1/categorias/{idCategoria}
     */
    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::findByIdController()");

        $idCategoria = (int) $args['idCategoria'];

        $categoria = $this->categoriaService->findByIdService($idCategoria);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'categorias' => $categoria
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Atualiza categoria.
     *
     * Endpoint:
     * PUT /api/v1/categorias/{idCategoria}
     */
    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::updateController()");

        $idCategoria = (int) $args['idCategoria'];

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $nomeCategoria = $objPHP->categoria->nomeCategoria;

        $this->categoriaService->updateService($idCategoria, $nomeCategoria);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'categorias' => [
                    [
                        'idCategoria' => $idCategoria,
                        'nomeCategoria' => $nomeCategoria
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Exclui categoria.
     *
     * Endpoint:
     * DELETE /api/v1/categorias/{idCategoria}
     */
    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::deleteController()");

        $idCategoria = (int) $args['idCategoria'];

        $this->categoriaService->deleteService($idCategoria);

        $resposta = [
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'categorias' => [
                    [
                        'idCategoria' => $idCategoria
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Conta total de categorias.
     *
     * Endpoint:
     * GET /api/v1/categorias/count
     */
    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CategoriaController::countController()");

        $total = $this->categoriaService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'count' => $total
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}