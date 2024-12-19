<?php
namespace nosmi\base;

use nosmi\Db;

abstract class Model
{
    protected array $attributes = [];
    protected array $errors = [];
    protected array $rules = [];
    protected \PDO $pdo;

    public function __construct()
    {
        $connection = Db::getInstance();
        $this->pdo = $connection->getPDO();
    }

    protected function load($data){
        foreach ($this->attributes as $k => $v) {
            if (isset($data[$k])) {
                $this->attributes[$k] = $data[$k];
            }
        }
    }
}