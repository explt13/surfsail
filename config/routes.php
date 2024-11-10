<?php
use nosmi\Router;


Router::add("^product/(?P<alias>[a-z0-9-]+)/?$", ['controller' => 'Product', 'action' => 'view']);
Router::add("^auth/?$", ['controller' => 'User', 'action' => 'auth']);

Router::add("^cart/(?P<action>get-products-list)/?$", ['controller' => 'Cart', 'auth' => false]);
Router::add("^cart/?(?P<action>[a-z-]+)?/?$", ['controller' => 'Cart', 'auth' => true]);

Router::add("^favorite/(?P<action>get-products-list)/?$", ['controller' => 'Favorite', 'auth' => false]);
Router::add("^favorite/?(?P<action>[a-z-]+)?/?$", ['controller' => 'Favorite', 'auth' => true]);


// default routes
Router::add('^admin$', ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);
Router::add('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$', ['prefix' => 'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$');