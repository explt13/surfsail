<?php

namespace nosmi;

class RouteContext
{
    private array $data = [];
    private array $readOnlyProperties = [];
    private bool $canOverwrite = false;

    public function setRoute(array $route)
    {
        $route = $this->prepareRoute($route);
        foreach ($route as $key => $value) {
            $this->data[$key] = $value;
            $this->readOnlyProperties[] = $key;
        }
    }

    private function prepareRoute(array $route)
    {
        if (empty($route['action'])) {
            $route['action'] = 'index';
        }
        if (!isset($route['prefix'])) {
            $route['prefix'] = '';
        } else {
            $route['prefix'] .= '\\';
        }
        $route['controller'] = $this->upperCamelCase($route['controller']);
        return $route;
    }

    private function upperCamelCase(string $str)
    {
        return str_replace('-', '', ucwords($str, '-'));
    }


    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        if (in_array($name, $this->readOnlyProperties)) {
            throw new \Exception("Cannot set read-only property: $name");
        }
        if (!$this->canOverwrite && isset($this->data[$name])){
            throw new \Exception("Cannot overwrite existing property: $name");
        }
        $this->data[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}