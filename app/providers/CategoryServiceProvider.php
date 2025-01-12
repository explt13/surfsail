<?php

namespace app\providers;

use app\models\CategoryModel;
use app\models\interfaces\CategoryModelInterface;
use app\providers\interfaces\ServiceProviderInterface;
use nosmi\App;

class CategoryServiceProvider implements ServiceProviderInterface
{
    protected CategoryModelInterface $category_model;

    public function __construct(CategoryModelInterface $category_model)
    {
        $this->category_model = $category_model;
    }
    public function register()
    {
        $categories = $this->category_model->getCategories();
        App::$registry->setProperty('categories', $categories);
    }
}