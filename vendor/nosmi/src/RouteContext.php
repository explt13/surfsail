<?php

namespace nosmi;

class RouteContext
{
    private array $data = [];
    private array $readOnlyProperties = [];
    private bool $canOverwrite = false; 

    public function __construct(array $props = [])
    {
        foreach ($props as $key => $value) {
            $this->data[$key] = $value;
            $this->readOnlyProperties[] = $key;
        }
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        if (!$this->canOverwrite){
            if (in_array($name, $this->readOnlyProperties)) {
                throw new \Exception("Cannot set read-only property: $name");
            }
            if (isset($this->data[$name])) {
                throw new \Exception("Cannot overwrite existing property: $name");
            }
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