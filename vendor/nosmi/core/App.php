<?php
namespace nosmi;

class App
{
    public static Registry $app;

    public function __construct()
    {
        $query = trim($_SERVER["QUERY_STRING"], '/');
        session_start();
        self::$app = Registry::getInstance(); // Registry::$instance => new Registry
        $this->setParams();
        new ErrorHandler();
        Router::dispatch($query);
    }

    protected function setParams()
    {
        $params = require_once CONF . "/params.php";
        if (!empty($params)){
            foreach($params as $k => $v){
                self::$app->setProperty($k, $v);
            }
        }
    }
}
