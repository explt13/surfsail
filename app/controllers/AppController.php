<?php
namespace app\controllers;

use nosmi\base\Controller;
use app\models\CurrencyModel;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use nosmi\App;


class AppController extends Controller
{

    protected $app_model;
    protected CurrencyModelInterface $currency_model;
    protected CategoryModelInterface $category_model;

    public function __construct(CurrencyModelInterface $currency_model, CategoryModelInterface $category_model)
    {
        $this->currency_model = $currency_model;
        $this->category_model = $category_model;

        App::$registry->setProperty('currencies', $this->currency_model->getCurrencies());
        App::$registry->setProperty('currency', CurrencyModel::getCurrencyByCookie(App::$registry->getProperty('currencies')));
        $this->category_model->getCategories();
    }
}