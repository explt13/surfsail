<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use \Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('DEBUG')->allowedValues(['0', '1']);

define("DEBUG", (int)$_ENV['DEBUG']);
define("ROOT", $_ENV['BASE_DIR']);
define("WWW", $_ENV['WWW']);
define("APP", $_ENV['APP']);
define("CORE", $_ENV['CORE']);
define("LIBS", $_ENV['LIBS']);
define("CACHE", $_ENV['CACHE']);
define("CONF", $_ENV['CONF']);
define("LAYOUT", $_ENV['LAYOUT']);

// http://surfsail.com/public/index.php
$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";

// http://surfsail.com/public/
$app_path = preg_replace("/[^\/]+$/", '', $app_path);

// http://surfsail.com
$app_path = preg_replace("/\/public\//", "", $app_path);

define("PATH", $app_path);
define("ADMIN", PATH . '/admin');
