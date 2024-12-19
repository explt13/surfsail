<?php

namespace nosmi;

interface ContainerInterface
{
    public function set(string $id, callable $callback): void;
    public function has(string $id): bool;
    public function get(string $id): object;
    public function make(string $service): object;
}