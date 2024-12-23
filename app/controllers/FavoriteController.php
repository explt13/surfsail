<?php
namespace app\controllers;

use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\FavoriteModelInterface;
use nosmi\App;

class FavoriteController extends BundleController
{
    protected $bundle_model;

    public function __construct(
        FavoriteModelInterface $bundle_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($bundle_model, $currency_model, $category_model);
    }
    
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $products = $this->bundle_model->getProductsFromArray();
        http_response_code(200);
        $this->setMeta("Favorite", "User's favorite products page", 'Favorite page, products, like');
        $this->setData(compact('currency', 'products'));
        $this->getView();
    }
}