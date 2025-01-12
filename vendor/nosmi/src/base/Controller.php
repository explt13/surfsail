<?php
namespace nosmi\base;

use nosmi\CacheInterface;
use nosmi\RouteContext;

abstract class Controller
{
    protected RouteContext $route;
    protected CacheInterface $cache;
    private array $data = [];
    private array $meta = ["title" => "", "description" => "", "keywords" => ""];

    public final function init(RouteContext $route_context)
    {
        $this->route = $route_context;
    }

    public function getView(): void
    {
        $view = new View($this->route->controller, $this->route->action, $this->route->prefix, $this->route->layout, $this->meta);
        $view->render($this->data);
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
    
    public function setMeta(?string $title, ?string $description = null, ?string $keywords = null)
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;

    }
}