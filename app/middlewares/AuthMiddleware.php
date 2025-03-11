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
            $redirect_after = $_SERVER['REQUEST_URI'];
            if (isAjax()) {
                $redirect_after = $_SERVER['HTTP_REFERER'] ? str_replace(DOMAIN, '', $_SERVER['HTTP_REFERER']) : '';
            }
            redirect('/auth', 'You have to be logged in', "?r_link=$redirect_after");
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