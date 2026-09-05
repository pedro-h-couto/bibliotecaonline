<?php

namespace Api;

use Slim\App;
use Psr\Http\Message\ServerRequestInterface;
use Api\Http\ErrorResponse;

use Api\Routes\AutorRouter;
use Api\Routes\CategoriaRouter;
use Api\Routes\LivroRouter;
use Api\Routes\LeitorRouter;
use Api\Routes\EmprestimoRouter;
use Api\Routes\AuthRouter;

class Server
{
    private App $app;

    private AutorRouter $autorRouter;
    private CategoriaRouter $categoriaRouter;
    private LivroRouter $livroRouter;
    private LeitorRouter $leitorRouter;
    private EmprestimoRouter $emprestimoRouter;
    private AuthRouter $authRouter;

    public function __construct(
        App $app,
        AutorRouter $autorRouter,
        CategoriaRouter $categoriaRouter,
        LivroRouter $livroRouter,
        LeitorRouter $leitorRouter,
        EmprestimoRouter $emprestimoRouter,
        AuthRouter $authRouter
    ) {
        $this->app = $app;

        $this->autorRouter = $autorRouter;
        $this->categoriaRouter = $categoriaRouter;
        $this->livroRouter = $livroRouter;
        $this->leitorRouter = $leitorRouter;
        $this->emprestimoRouter = $emprestimoRouter;
        $this->authRouter = $authRouter;

        $this->setupMiddlewares();
        $this->setupRoutes();
        $this->setupErrorHandling();
    }

    private function setupMiddlewares(): void
    {
        $this->app->addBodyParsingMiddleware();

        // CORS — responde OPTIONS imediatamente, antes de qualquer auth
        $this->app->add(function ($request, $handler) {
            if ($request->getMethod() === 'OPTIONS') {
                $response = new \Slim\Psr7\Response();
                return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                    ->withHeader('Access-Control-Max-Age', '86400')
                    ->withStatus(200);
            }

            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->withHeader('Access-Control-Max-Age', '86400');
        });
    }

    private function setupRoutes(): void
    {
        $this->authRouter->setupRoutes();

        $this->autorRouter->setupRoutes();
        $this->categoriaRouter->setupRoutes();
        $this->livroRouter->setupRoutes();
        $this->leitorRouter->setupRoutes();
        $this->emprestimoRouter->setupRoutes();

        $this->app->get('/', function ($request, $response) {
            $payload = [
                'success' => true,
                'message' => 'API Biblioteca Online',
                'version' => '2.0',
                'auth' => 'JWT Required for all endpoints except /login'
            ];

            $response->getBody()->write(
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        });
    }

    private function setupErrorHandling(): void
    {
        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);

        $errorMiddleware->setDefaultErrorHandler(
            function (ServerRequestInterface $request, \Throwable $exception) {
                $response = new \Slim\Psr7\Response();
                $status = 500;

                if ($exception instanceof ErrorResponse) {
                    $payload = [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'error' => $exception->getError() ?? (object)[]
                    ];
                    $status = $exception->getHttpCode();
                } else {
                    $payload = [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'error' => [
                            'code' => $exception->getCode(),
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine()
                        ]
                    ];
                }

                $response->getBody()->write(
                    json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($status);
            }
        );
    }

    public function run(): void
    {
        $this->app->run();
    }
}
