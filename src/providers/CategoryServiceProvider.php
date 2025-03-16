<?php

namespace Surfsail\providers;

use Surfsail\models\CategoryModel;
use Surfsail\models\interfaces\CategoryModelInterface;
use Surfsail\providers\interfaces\ServiceProviderInterface;
use Explt13\Nosmi\App;

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