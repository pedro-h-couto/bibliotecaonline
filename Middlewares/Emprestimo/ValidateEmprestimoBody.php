<?php

namespace Api\Middlewares\Emprestimo;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

class ValidateEmprestimoBody
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $body = json_decode($request->getBody()->getContents());

        if (!$body) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'JSON invalido'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (
            !isset($body->dataEmprestimo) ||
            !isset($body->idLivro) ||
            !isset($body->idLeitor) ||
            !isset($body->status) ||
            trim((string)$body->status) === ''
        ) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Campos obrigatorios nao informados: dataEmprestimo, idLivro, idLeitor, status'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
}
