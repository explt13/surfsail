<?php
namespace nosmi\base;

abstract class Controller
{
    public array $route;
    private string $controller;
    private string $model;
    private string $view;
    private $layout;
    private string $prefix;
    private array $data = [];
    private array $meta = ["title" => "", "description" => "", "keywords" => ""];

    public function __construct(array $route)
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    public function getView(): void
    {
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta);
        $viewObject->render($this->data);
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
    
    public function setMeta(string $title, string $description, string $keywords)
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;

    }
}