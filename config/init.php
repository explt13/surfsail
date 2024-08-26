<?php

define("DEBUG", 1);
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("CORE", ROOT . '/vendor/nosmi/core');
define("LIBS", ROOT . '/vendor/nosmi/core/libs');
define("CACHE", ROOT . '/tmp/cache');
define("CONF", ROOT . '/config');
define("LAYOUT", 'surfsail');


// http://surfsail/public/index.php
$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";

// http://surfsail/public/
$app_path = preg_replace("/[^\/]+$/", '', $app_path);

// http://surfsail
$app_path = preg_replace("/\/public\//", "", $app_path);

define("PATH", $app_path);
define("ADMIN", PATH . '/admin');

require_once ROOT . '/vendor/autoload.php';
