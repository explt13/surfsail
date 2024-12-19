<?php
namespace nosmi\base;

use nosmi\RouteContext;

abstract class Controller
{
    // private string $controller;
    // private string $model;
    // protected string $view;
    // protected $layout;
    // private string $prefix;
    protected RouteContext $route;
    private array $data = [];
    private array $meta = ["title" => "", "description" => "", "keywords" => ""];

    public function init(RouteContext $route_context)
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