<?php
namespace nosmi;

trait SingletonTrait{
    private static $instance;
    
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}