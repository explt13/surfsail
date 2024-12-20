<?php
namespace app\middlewares;

use app\middlewares\interfaces\AuthMiddlewareInterface;

class AuthMiddleware implements AuthMiddlewareInterface
{
    public function CheckAuth($route) {
        if (isset($_SESSION['user']) && $route['controller'] === 'Auth' && $route['action'] === 'index') {
            redirect();
        }
        if (isset($route['auth']) && $route['auth'] === true && !isset($_SESSION['user'])) {
            redirect('auth');
        }
    }
    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }
}