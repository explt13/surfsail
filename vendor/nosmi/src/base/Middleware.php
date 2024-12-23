<?php

namespace nosmi\base;

class Middleware
{
    protected array $middlewares;

    public function __construct()
    {
        $this->middlewares = [
            'Auth',
        ];
    }
    
    public function run()
    {
        foreach ($this->middlewares as $middleware) {
            $middlewareClass = "app\\middlewares\\" . $middleware . "Middleware";
            
        }
    }
}