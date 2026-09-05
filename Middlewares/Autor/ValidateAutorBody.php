<?php

namespace Api\Middlewares\Autor;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

class ValidateAutorBody
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $body = json_decode($request->getBody()->getContents());

        if (!$body || !isset($body->nome) || !isset($body->nacionalidade) || trim((string)$body->nome) === '' || trim((string)$body->nacionalidade) === '') {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Nome e nacionalidade do autor sao obrigatorios'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        return $handler->handle($request);
    }
}
