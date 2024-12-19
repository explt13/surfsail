<?php

namespace app\middlewares\interfaces;

interface AuthMiddlewareInterface extends MiddlewareInterface
{
    public function CheckAuth($route);
    public static function isLoggedIn();
}