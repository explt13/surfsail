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

// https://surfsail.com
$app_path = "https://{$_SERVER['HTTP_HOST']}";

define("DOMAIN", $app_path);
define("ADMIN_DOMAIN", DOMAIN . '/admin');
