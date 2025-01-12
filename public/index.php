<?php

use nosmi\App;

require_once dirname(__DIR__) . '/config/init.php';
require_once LIBS . '/functions.php';

$app = new App();
$app->bootstrap();
$app->run();