<?php

namespace Api\Middlewares\Validation;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class ValidateBody
{
    private array $requiredFields;

    public function __construct(array $requiredFields)
    {
        $this->requiredFields = $requiredFields;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $data = json_decode($request->getBody()->getContents());

        if (!$data) {
            return $this->error("JSON inválido");
        }

        foreach ($this->requiredFields as $field) {
            if (!isset($data->$field)) {
                return $this->error("Campo obrigatório: $field");
            }
        }

        return $handler->handle($request);
    }

    private function error(string $message): Response
    {
        $response = new SlimResponse();

        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message
        ]));

        return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
}