<?php

namespace nosmi\base;

use nosmi\base\WidgetInterface;
use nosmi\CacheInterface;

abstract class Widget implements WidgetInterface
{
    protected string $tpl;
    public function render()
    {
        require_once $this->tpl;
    }
}