<?php

namespace Surfsail\middlewares;

use Explt13\Nosmi\interfaces\MiddlewareInterface;

class CorsMiddleware implements MiddlewareInterface
{
    private array $allowed_origins = [];
    
    private function cors()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if (in_array($_SERVER['HTTP_ORIGIN'], $this->allowed_origins)){
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            }
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
            exit(0);
        }
    }
    
    public function run()
    {
        $this->cors();
    }
}