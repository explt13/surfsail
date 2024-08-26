<?php
namespace nosmi;


class Db{
    use SingletonTrait;
    
    protected function __construct()
    {
        $db = require_once CONF . '/db_connection.php';
        class_alias("RedBeanPHP\R", "\R");
        \R::setup($db['dsn'], $db['user'], $db['password']);
        if (!\R::testConnection()) {
            throw new \Exception("Can not connect to the database", 500);
        }
        \R::freeze(true);
        if (DEBUG) {
            \R::debug(true, 1);
        }
    }
}