<?php

namespace Surfsail\middlewares;

use Explt13\Nosmi\Interfaces\LightResponseInterface;
use Explt13\Nosmi\Interfaces\LightServerRequestInterface;
use Explt13\Nosmi\Middleware\Middleware;
use Surfsail\models\UserModel;

class AuthenticationMiddleware extends Middleware
{
    protected function processRequest(LightServerRequestInterface $request): ?LightServerRequestInterface
    {
        $user_model = new UserModel;
        $remembered = $user_model->isUserAuthenticated();
        if ($remembered && empty($_SESSION['user'])) {
            $user = $user_model->getUserById($remembered['user_id']);
            $user_model->authenticate($user);
        }
        return $request;
    }

    protected function processResponse(LightResponseInterface $response, LightServerRequestInterface $request): LightResponseInterface
    {
        return $response;
    }
}