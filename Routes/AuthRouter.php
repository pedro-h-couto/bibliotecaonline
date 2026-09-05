<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\AuthController;

class AuthRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $this->app->post('/login', [AuthController::class, 'login']);
    }
}
