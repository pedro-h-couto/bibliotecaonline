<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\EmprestimoService;

class EmprestimoController
{
    private EmprestimoService $emprestimoService;

    public function __construct(EmprestimoService $emprestimoService)
    {
        $this->emprestimoService = $emprestimoService;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents());

        $emp = $this->emprestimoService->createService($body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'emprestimos' => [[
                    'idEmprestimo' => $emp->getIdEmprestimo(),
                    'status' => $emp->getStatus()
                ]]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        $lista = $this->emprestimoService->findAllService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['emprestimos' => $lista]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idEmprestimo'];

        $emp = $this->emprestimoService->findByIdService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['emprestimos' => $emp]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idEmprestimo'];
        $body = json_decode($request->getBody()->getContents());

        $emp = $this->emprestimoService->updateService($id, $body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => ['emprestimos' => [$emp]]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idEmprestimo'];

        $this->emprestimoService->deleteService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Excluido com sucesso'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        $qtd = $this->emprestimoService->countService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => ['count' => $qtd]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
