<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\LivroController;
use Api\Middlewares\AuthMiddleware;
use Api\Middlewares\Livro\ValidateLivroBody;
use Api\Middlewares\Livro\ValidateLivroId;

class LivroRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $auth = AuthMiddleware::class;

        $this->app->post('/livros', [LivroController::class, 'createController'])
            ->add(ValidateLivroBody::class)
            ->add($auth);

        $this->app->get('/livros', [LivroController::class, 'findAllController'])
            ->add($auth);

        $this->app->get('/livros/count', [LivroController::class, 'countController'])
            ->add($auth);

        $this->app->get('/livros/{idLivro}', [LivroController::class, 'findByIdController'])
            ->add(ValidateLivroId::class)
            ->add($auth);

        $this->app->put('/livros/{idLivro}', [LivroController::class, 'updateController'])
            ->add(ValidateLivroBody::class)
            ->add(ValidateLivroId::class)
            ->add($auth);

        $this->app->delete('/livros/{idLivro}', [LivroController::class, 'deleteController'])
            ->add(ValidateLivroId::class)
            ->add($auth);
    }
}
