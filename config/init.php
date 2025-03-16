<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use \Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('DEBUG')->allowedValues(['0', '1']);

define("DEBUG", (int)$_ENV['DEBUG']);
define("ROOT", $_ENV['BASE_DIR']);
define("PUBLIC_DIR", $_ENV['PUBLIC_DIR']);
define("APP", $_ENV['APP']);
define("FRAMEWORK_SRC", $_ENV['FRAMEWORK_SRC']);
define("LIBS", $_ENV['LIBS']);
define("CACHE", $_ENV['CACHE']);
define("CONF", $_ENV['CONF']);
define("LAYOUT", $_ENV['LAYOUT']);

// https://surfsail.com
$domain = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}";

define("DOMAIN", $domain);
define("ADMIN_DOMAIN", DOMAIN . '/admin');
