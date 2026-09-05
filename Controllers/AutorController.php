<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\AutorService;

class AutorController
{
    private AutorService $autorService;

    public function __construct(AutorService $autorService)
    {
        $this->autorService = $autorService;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents());

        $autor = $this->autorService->createService($body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'autores' => [
                    [
                        'idAutor' => $autor->getIdAutor(),
                        'nome' => $autor->getNome()
                    ]
                ]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        $lista = $this->autorService->findAllService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['autores' => $lista]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idAutor'];

        $autor = $this->autorService->findByIdService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['autores' => $autor]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idAutor'];
        $body = json_decode($request->getBody()->getContents());

        $autor = $this->autorService->updateService($id, $body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => ['autores' => [$autor]]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idAutor'];

        $this->autorService->deleteService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Excluído com sucesso'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}