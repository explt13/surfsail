<?php
namespace nosmi;

class App
{
    public static Registry $registry;

    public function __construct()
    {
        $query = $_SERVER["QUERY_STRING"];
        session_start();
        cors();
        self::$registry = Registry::getInstance(); // Registry::$instance => new Registry;
        ErrorHandler::getInstance();
        Router::dispatch($query);
    }
}
