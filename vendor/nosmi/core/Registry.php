<?php
namespace nosmi;

class Registry
{
    use SingletonTrait;

    private $properties = [];

    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }
    public function getProperty($name)
    {
        return $this->properties[$name] ?? null;
    }
    public function getProperties()
    {
        return $this->properties;
    }
}