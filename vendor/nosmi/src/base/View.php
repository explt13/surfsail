<?php
namespace nosmi\base;

class View
{
    private string $controller;
    private string $view;
    private string | false | null $layout;
    private string $prefix;
    private array $meta = []; // app/views/layouts/meta.php

    public function __construct(string $controller, string $action, string $prefix, ?string $layout, $meta)
    {
        $this->controller = $controller;
        $this->view = $action;
        $this->prefix = $prefix;
        $this->layout = $layout ?? LAYOUT;
        $this->meta = $meta;
    }
    
    public function render(array $data)
    {
        extract($data);
        $viewFile = APP . "/views/" . $this->prefix . $this->controller . '/' . $this->view . '.php';
        if (is_file($viewFile)) {
            ob_start();
            require_once $viewFile;
            $view = ob_get_clean();
        } else {
            throw new \Exception("View not found: {$this->view}", 500);
        }
        $layoutFile = APP . "/views/layouts/" . $this->layout . '.php';
        if (is_file($layoutFile)) {
            require_once $layoutFile;
        } else {
            throw new \Exception("Layout not found: {$this->layout}", 500);
        }
    }
    
    private function getMeta()
    {
        $metaFile = APP . '/views/layouts/_meta.php';
        if (is_file($metaFile)) {
            require_once $metaFile;
        } else {
            throw new \Exception("No meta $metaFile file found", 404);
        }
    }
}