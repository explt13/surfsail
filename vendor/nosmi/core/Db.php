<?php
namespace nosmi;

use RedBeanPHP\R;

class Db{
    use SingletonTrait;
    
    protected function __construct()
    {
        $db = require_once CONF . '/db_connection.php';
       
        R::setup($db['dsn'], $db['user'], $db['password']);
        if (!R::testConnection()) {
            throw new \Exception("Can not connect to the database", 500);
        } else {
            echo "Connection established";
        }
        R::freeze(true);
        if (DEBUG) {
            R::debug(true, 1);
        }
    }
}