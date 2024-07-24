<?php
namespace nosmi\base;

use nosmi\Db;

abstract class Model
{
    protected array $attributes = [];
    protected array $errors = [];
    protected array $rules = [];

    public function __construct()
    {
        Db::getInstance();
    }
}