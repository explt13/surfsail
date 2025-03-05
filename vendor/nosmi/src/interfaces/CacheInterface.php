<?php

namespace nosmi\interfaces;

interface CacheInterface
{
    public function set($key, $data, $seconds=3600);
    public function get($key);
    public function delete($key);
}