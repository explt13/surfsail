<?php
namespace nosmi;

class App
{
    public static Registry $registry;

    public function __construct()
    {
        $query = $_SERVER["QUERY_STRING"];
        session_start();
        self::$registry = Registry::getInstance(); // Registry::$instance => new Registry
        $this->setParams();
        ErrorHandler::getInstance();
        Router::dispatch($query);
    }

    protected function setParams()
    {
        $params = require_once CONF . "/params.php";
        if (!empty($params)){
            foreach($params as $k => $v){
                self::$registry->setProperty($k, $v);
            }
        }
    }
}
