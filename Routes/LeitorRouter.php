<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\LeitorController;
use Api\Middlewares\AuthMiddleware;

class LeitorRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $auth = AuthMiddleware::class;

        $this->app->group('/leitores', function ($group) use ($auth) {
            $group->post('', [LeitorController::class, 'createController'])->add($auth);
            $group->get('', [LeitorController::class, 'findAllController'])->add($auth);
            $group->get('/count', [LeitorController::class, 'countController'])->add($auth);
            $group->get('/{idLeitor}', [LeitorController::class, 'findByIdController'])->add($auth);
            $group->put('/{idLeitor}', [LeitorController::class, 'updateController'])->add($auth);
            $group->delete('/{idLeitor}', [LeitorController::class, 'deleteController'])->add($auth);
        });
    }
}
