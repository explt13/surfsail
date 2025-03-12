<?php

return [
    "^product/(?P<alias>[a-z0-9-.]+)/?$" => ['controller' => 'Product'],
    "^auth/?$" => ['controller' => 'Auth', 'action' => 'index', 'layout' => 'clean'],
    "^cart/(?P<action>get-added-items)/?$" => ['controller' => 'Cart', 'secured' => false],
    "^cart/?(?P<action>[a-z-]+)?/?$" => ['controller' => 'Cart', 'secured' => true],
    "^favorite/(?P<entity>[a-z]+)/(?P<action>get-added-items)/?$" => ['controller' => 'Favorite', 'secured' => false],
    "^favorite/(?P<entity>[a-z]+)/(?P<action>delete)/?$" => ['controller' => 'Favorite', 'secured' => true],
    "^favorite/?(?P<action>[a-z-]+)?/?$" => ['controller' => 'Favorite', 'secured' => true],
    
    // default routes
    "^admin$" => ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin'],
    "^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$" => ['prefix' => 'admin'],
    "^$" => ['controller' => 'Main', 'action' => 'index'],
    "^(?P<controller>(?!main$)[a-z-]+)/?(?P<action>[a-z-]+)?/?$" => ['action' => 'index'],
];