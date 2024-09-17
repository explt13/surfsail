<?php
namespace app\controllers;

use nosmi\base\Controller;
use app\models\AppModel;
use app\models\CategoryModel;
use app\widgets\currency\Currency;
use nosmi\App;
use nosmi\Cache;

abstract class AppController extends Controller
{
    public function __construct(array $route)
    {
        parent::__construct($route);
        new AppModel();
        App::$registry->setProperty('currencies', Currency::getCurrencies());
        App::$registry->setProperty('currency', Currency::getCurrency(App::$registry->getProperty('currencies')));
        $this->getCategories();
    }

    private function getCategories()
    {
        $cache = Cache::getInstance();
        $categories = $cache->get('categories');
        $category_model = new CategoryModel();
        if (!$categories) {
            $categories = $category_model->getCategories();
            $cache->set('categories', $categories, 3600 * 24 * 7 * 30);
        }

        App::$registry->setProperty('categories', $categories);
        return;
    }
}