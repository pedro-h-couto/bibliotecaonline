<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\LivroService;

class LivroController
{
    private LivroService $livroService;

    public function __construct(LivroService $livroService)
    {
        error_log("⬆️ LivroController::__construct()");
        $this->livroService = $livroService;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents());

        $resultado = $this->livroService->createService($body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'livros' => [
                    [
                        'idLivro' => $resultado->getIdLivro(),
                        'titulo' => $resultado->getTitulo()
                    ]
                ]
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        $lista = $this->livroService->findAllService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'livros' => $lista
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLivro'];

        $livro = $this->livroService->findByIdService($id);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'livros' => $livro
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        $qtd = $this->livroService->countService();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'count' => $qtd
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLivro'];

        $body = json_decode($request->getBody()->getContents(), true);

        $resultado = $this->livroService->updateService($id, $body);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'livros' => [
                    $resultado
                ]
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['idLivro'];

        $excluiu = $this->livroService->deleteService($id);

        $response->getBody()->write(json_encode([
            'success' => $excluiu,
            'message' => $excluiu ? 'Excluído com sucesso' : 'Livro não encontrado'
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($excluiu ? 200 : 404);
    }
}