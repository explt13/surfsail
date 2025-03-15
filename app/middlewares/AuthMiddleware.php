<?php
namespace app\middlewares;

use app\models\UserModel;
use Explt13\Nosmi\App;
use Explt13\Nosmi\interfaces\MiddlewareInterface;
use Explt13\Nosmi\RouteContext;

class AuthMiddleware implements MiddlewareInterface
{
    private RouteContext $route;
    private UserModel $user_model;
    public function __construct(RouteContext $route, UserModel $user_model)
    {
        $this->route = $route;
        $this->user_model = $user_model;
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

    protected function logInUserIfRemembered()
    {
        if (!isset($_SESSION['user'])) {
            $this->user_model->loginRemembered();
        }
    }

    protected function setIsLoggedIn()
    {
        App::$registry->setProperty('loggedIn', isset($_SESSION['user']));
    }

    public function run()
    {
        $this->checkAuth();
        $this->logInUserIfRemembered();
        $this->setIsLoggedIn();
    }
}