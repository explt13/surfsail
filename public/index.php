<?php

use Explt13\Nosmi\Base\App;
use Explt13\Nosmi\Middleware\CorsMiddleware;
use Explt13\Nosmi\Routing\Route;
use Surfsail\access\Roles;
use Surfsail\middlewares\AccessMiddleware;
use Surfsail\middlewares\AuthenticationMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Route::useMiddleware('/cart/buy', new AccessMiddleware([Roles::admin, Roles::user]));
$app = (new App())
            ->bootstrap(dirname(__DIR__) . '/config/.env')
            ->use(new AuthenticationMiddleware())
            ->use(new CorsMiddleware())
            ->run();
