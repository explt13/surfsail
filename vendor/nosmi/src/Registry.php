<?php
namespace nosmi;

class Registry
{
    use SingletonTrait;

    private $properties = [];

    protected function __construct()
    {
        $this->setParams();
    }

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

    public function setParams()
    {
        $params = require_once CONF . "/params.php";
        if (!empty($params)){
            foreach($params as $k => $v){
                $this->setProperty($k, $v);
            } 
        }
    }
}