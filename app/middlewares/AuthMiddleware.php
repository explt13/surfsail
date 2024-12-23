<?php
namespace app\middlewares;

use app\middlewares\interfaces\AuthMiddlewareInterface;
use nosmi\App;
use nosmi\RouteContext;

class AuthMiddleware implements AuthMiddlewareInterface
{
    public function CheckAuth(RouteContext $route) {
        if (isset($_SESSION['user']) && $route->controller === 'Auth' && $route->action === 'index') {
            redirect();
        }
        if (isset($route->auth) && $route->auth === true && !isset($_SESSION['user'])) {
            redirect('auth');
        }
    }
    public static function setIsLoggedIn() {
        App::$registry->setProperty('loggedIn', isset($_SESSION['user']));
    }
}