<?php

namespace Api\Services;

use Api\DAO\UsuarioDAO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Api\Http\ErrorResponse;

class AuthService
{
    private UsuarioDAO $usuarioDAO;
    private string $secret = 'biblioteca-jwt-secret-2026';

    public function __construct(UsuarioDAO $usuarioDAO)
    {
        $this->usuarioDAO = $usuarioDAO;
    }

    public function login(string $email, string $senha): string
    {
        $usuario = $this->usuarioDAO->findByEmail($email);

        if (!$usuario) {
            throw new ErrorResponse(401, "Credenciais invalidas", ["message" => "Email nao encontrado"]);
        }

        if (!password_verify($senha, $usuario->getSenha())) {
            throw new ErrorResponse(401, "Credenciais invalidas", ["message" => "Senha incorreta"]);
        }

        $payload = [
            'iss' => 'biblioteca-api',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 8),
            'sub' => $usuario->getIdUsuario(),
            'nome' => $usuario->getNome(),
            'email' => $usuario->getEmail()
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validateToken(string $token): object
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            throw new ErrorResponse(401, "Token invalido", ["message" => $e->getMessage()]);
        }
    }
}
