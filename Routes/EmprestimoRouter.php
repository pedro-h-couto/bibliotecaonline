<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\EmprestimoController;
use Api\Middlewares\AuthMiddleware;
use Api\Middlewares\Emprestimo\ValidateEmprestimoBody;
use Api\Middlewares\Emprestimo\ValidateEmprestimoId;

class EmprestimoRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $auth = AuthMiddleware::class;

        $this->app->post('/emprestimos', [EmprestimoController::class, 'createController'])
            ->add(ValidateEmprestimoBody::class)
            ->add($auth);

        $this->app->get('/emprestimos', [EmprestimoController::class, 'findAllController'])
            ->add($auth);

        $this->app->get('/emprestimos/count', [EmprestimoController::class, 'countController'])
            ->add($auth);

        $this->app->get('/emprestimos/{idEmprestimo}', [EmprestimoController::class, 'findByIdController'])
            ->add(ValidateEmprestimoId::class)
            ->add($auth);

        $this->app->put('/emprestimos/{idEmprestimo}', [EmprestimoController::class, 'updateController'])
            ->add(ValidateEmprestimoBody::class)
            ->add(ValidateEmprestimoId::class)
            ->add($auth);

        $this->app->delete('/emprestimos/{idEmprestimo}', [EmprestimoController::class, 'deleteController'])
            ->add(ValidateEmprestimoId::class)
            ->add($auth);
    }
}
