<?php

namespace Api\Middlewares\Livro;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

/**
 * Middleware responsável por validar o body
 * das requisições relacionadas ao recurso Livro.
 *
 * Estrutura esperada:
 *
 * {
 *   "livro": {
 *     "titulo": "Dom Casmurro",
 *     "anoPublicacao": 1899,
 *     "quantidade": 5,
 *     "autor": {
 *       "idAutor": 1
 *     },
 *     "categoria": {
 *       "idCategoria": 1
 *     }
 *   }
 * }
 */
class ValidateLivroBody implements MiddlewareInterface
{
    public function process(
        Request $request,
        RequestHandler $handler
    ): Response
    {
        $body = $request->getBody()->getContents();

        $objPHP = json_decode($body);

        if (!isset($objPHP->livro)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'livro' é obrigatório!"
                ]
            );
        }

        $livro = $objPHP->livro;

        $camposObrigatorios = [
            "titulo",
            "anoPublicacao",
            "quantidade"
        ];

        foreach ($camposObrigatorios as $campo) {

            if (
                !isset($livro->$campo) ||
                $livro->$campo === "" ||
                $livro->$campo === null
            ) {
                throw new ErrorResponse(
                    httpCode: 400,
                    message: "Erro na validação de dados",
                    error: [
                        "message" => "O campo '{$campo}' é obrigatório!"
                    ]
                );
            }
        }

        if (
            !is_int($livro->anoPublicacao) ||
            $livro->anoPublicacao <= 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'anoPublicacao' deve ser um número inteiro positivo"
                ]
            );
        }

        if (
            !is_int($livro->quantidade) ||
            $livro->quantidade < 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'quantidade' deve ser um número inteiro maior ou igual a zero"
                ]
            );
        }

        if (
            !isset($livro->autor) ||
            !isset($livro->autor->idAutor) ||
            !is_int($livro->autor->idAutor) ||
            $livro->autor->idAutor <= 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'idAutor' deve ser um número inteiro positivo"
                ]
            );
        }

        if (
            !isset($livro->categoria) ||
            !isset($livro->categoria->idCategoria) ||
            !is_int($livro->categoria->idCategoria) ||
            $livro->categoria->idCategoria <= 0
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" =>
                        "O campo 'idCategoria' deve ser um número inteiro positivo"
                ]
            );
        }

        return $handler->handle($request);
    }
}