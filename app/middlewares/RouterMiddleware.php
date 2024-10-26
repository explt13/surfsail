<?php
namespace app\middlewares;

class RouterMiddleware
{
    public function CheckSecure($route) {
        if (isset($_SESSION['user']) && $route['controller'] === 'Auth' && $route['action'] === 'index') {
            redirect();
        }
        if (isset($route['auth']) && $route['auth'] === true && !isset($_SESSION['user'])) {
            redirect('auth');
        }
    }
}