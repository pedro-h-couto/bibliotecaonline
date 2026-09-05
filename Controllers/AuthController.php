<?php

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\AuthService;
use Api\Http\ErrorResponse;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $body = json_decode($request->getBody()->getContents());

        if (!isset($body->email) || !isset($body->senha)) {
            throw new ErrorResponse(400, "Email e senha sao obrigatorios");
        }

        $token = $this->authService->login($body->email, $body->senha);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
