<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\CategoriaController;
use Api\Middlewares\AuthMiddleware;
use Api\Middlewares\Categoria\ValidateCategoriaBody;
use Api\Middlewares\Categoria\ValidateCategoriaId;

class CategoriaRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $auth = AuthMiddleware::class;

        $this->app->post('/categorias', [CategoriaController::class, 'createController'])
            ->add(ValidateCategoriaBody::class)
            ->add($auth);

        $this->app->get('/categorias', [CategoriaController::class, 'findAllController'])
            ->add($auth);

        $this->app->get('/categorias/count', [CategoriaController::class, 'countController'])
            ->add($auth);

        $this->app->get('/categorias/{idCategoria}', [CategoriaController::class, 'findByIdController'])
            ->add(ValidateCategoriaId::class)
            ->add($auth);

        $this->app->put('/categorias/{idCategoria}', [CategoriaController::class, 'updateController'])
            ->add(ValidateCategoriaBody::class)
            ->add(ValidateCategoriaId::class)
            ->add($auth);

        $this->app->delete('/categorias/{idCategoria}', [CategoriaController::class, 'deleteController'])
            ->add(ValidateCategoriaId::class)
            ->add($auth);
    }
}
