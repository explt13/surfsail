<?php
namespace app\middlewares;

use app\middlewares\interfaces\MiddlewareInterface;
use nosmi\App;
use nosmi\base\Middleware;
use nosmi\RouteContext;

class AuthMiddleware extends Middleware
{
    private RouteContext $route;
    public function __construct(RouteContext $route)
    {
        $this->route = $route;
    }

    protected function CheckAuth()
    {
        if (isset($_SESSION['user']) && $this->route->controller === 'Auth' && $this->route->action === 'index') {
            redirect();
        }
        if (isset($this->route->auth) && $this->route->auth === true && !isset($_SESSION['user'])) {
            redirect('auth');
        }
    }

    protected function setIsLoggedIn()
    {
        App::$registry->setProperty('loggedIn', isset($_SESSION['user']));
    }

    public function run()
    {
        $this->checkAuth();
        $this->setIsLoggedIn();
    }
}