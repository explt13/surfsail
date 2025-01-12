<?php

namespace nosmi;

interface ContainerInterface
{
    public function init(array $dependencies);
    public function set(string $id, callable $callback): void;
    public function has(string $id): bool;
    public function get(string $id): object;
    public function autowire(string $service): object;
}