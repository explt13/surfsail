<?php
namespace nosmi;

class Registry
{
    use SingletonTrait;

    private static $properties = [];

    public function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }
    public function getProperty($name)
    {
        return self::$properties[$name] ??= null;
    }
    public function getProperties()
    {
        return self::$properties;
    }
}