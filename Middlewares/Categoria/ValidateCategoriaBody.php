<?php

namespace Api\Middlewares\Categoria;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateCategoriaBody implements MiddlewareInterface
{
    public function process(
        Request $request,
        RequestHandler $handler
    ): Response
    {
        $body = $request->getBody()->getContents();

        $objPHP = json_decode($body);

        if (!isset($objPHP->categoria)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'categoria' é obrigatório!"
                ]
            );
        }

        $categoria = $objPHP->categoria;

        if (
            !isset($categoria->nomeCategoria) ||
            trim((string)$categoria->nomeCategoria) === ""
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'nomeCategoria' é obrigatório!"
                ]
            );
        }

        return $handler->handle($request);
    }
}