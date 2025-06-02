<?php

namespace Surfsail\widgets;

use Explt13\Nosmi\Base\Widget;
use Surfsail\services\CategoryService;

class Menu extends Widget
{
    protected $categories;
    private CategoryService $category_service;
    
    public function __construct(CategoryService $category_service)
    {
        $this->category_service = $category_service;
        $this->tpl = __DIR__ . '/tpl/menu.php';
        $this->prepareData();
    }

    protected function prepareData()
    {
        $this->categories = $this->category_service->getCategories();
    }
}