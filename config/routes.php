<?php

return [
    "^product/(?P<alias>[a-z0-9-.]+)/?$" => ['controller' => 'Product', 'action' => 'view'],
    "^auth/?$" => ['controller' => 'User', 'action' => 'auth', 'layout' => 'clean'],
    "^cart/(?P<action>get-products-list)/?$" => ['controller' => 'Cart', 'auth' => false],
    "^cart/?(?P<action>[a-z-]+)?/?$" => ['controller' => 'Cart', 'auth' => true],
    "^favorite/(?P<action>get-products-list)/?$" => ['controller' => 'Favorite', 'auth' => false],
    "^favorite/?(?P<action>[a-z-]+)?/?$" => ['controller' => 'Favorite', 'auth' => true],
    
    // default routes
    "^admin$" => ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin'],
    "^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$" => ['prefix' => 'admin'],
    "^$" => ['controller' => 'Main', 'action' => 'index'],
    "^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$" => ['action' => 'index'],
];