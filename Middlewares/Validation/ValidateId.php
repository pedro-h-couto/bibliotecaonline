<?php

namespace Api\Middlewares\Validation;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class ValidateId
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $id = $request->getAttribute('id');

        if (!$id || !is_numeric($id) || $id <= 0) {
            $response = new SlimResponse();

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'ID inválido'
            ]));

            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}