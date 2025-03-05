<?php
namespace app\middlewares;

use nosmi\App;
use nosmi\interfaces\MiddlewareInterface;
use nosmi\RouteContext;

class AuthMiddleware implements MiddlewareInterface
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
        if (isset($this->route->secured) && $this->route->secured === true && !isset($_SESSION['user'])) {
            redirect('/auth', 'You have to be logged in');
            exit;
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