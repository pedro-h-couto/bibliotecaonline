<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\LeitorService;

class LeitorController
{
    private LeitorService $leitorService;

    public function __construct(LeitorService $leitorService)
    {
        $this->leitorService = $leitorService;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents());

        $leitor = $this->leitorService->createService($body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'leitores' => [[
                    'idLeitor' => $leitor->getIdLeitor(),
                    'nome' => $leitor->getNome()
                ]]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        $lista = $this->leitorService->findAllService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['leitores' => $lista]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLeitor'];

        $leitor = $this->leitorService->findByIdService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['leitores' => $leitor]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLeitor'];
        $body = json_decode($request->getBody()->getContents());

        $leitor = $this->leitorService->updateService($id, $body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => ['leitores' => [$leitor]]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLeitor'];

        $this->leitorService->deleteService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Excluido com sucesso'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        $qtd = $this->leitorService->countService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['count' => $qtd]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
