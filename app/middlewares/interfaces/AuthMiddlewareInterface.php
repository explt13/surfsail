<?php

namespace app\middlewares\interfaces;

use nosmi\RouteContext;

interface AuthMiddlewareInterface
{
    public function CheckAuth(RouteContext $route);
    public static function setIsLoggedIn();
}