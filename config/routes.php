<?php
use nosmi\Router;


Router::add("^product/(?P<alias>[a-z0-9-]+)/?$", ['controller' => 'Product', 'action' => 'view']);
Router::add("^auth/(?P<action>[a-z-]+)/?$", ['controller' => 'Auth', 'auth' => false]);

// default routes
Router::add('^admin$', ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);
Router::add('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$', ['prefix' => 'admin']);

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?/?$');