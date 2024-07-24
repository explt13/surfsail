<?php
namespace app\controllers;

use nosmi\Cache;
use RedBeanPHP\R;

class MainController extends AppController
{ 
    public function indexAction()
    {
        $this->setMeta(\nosmi\App::$app->getProperty("app_name"), 'some description', 'test1, test2');
        $data = "Hello, World";
        $this->setData(compact('data'));
    }
}