<?php
namespace app\controllers;

use app\models\interfaces\FavoriteModelInterface;
use nosmi\App;

class FavoriteController extends BundleController
{
    protected $bundle_model;

    public function __construct(FavoriteModelInterface $bundle_model)
    {
        $this->bundle_model = $bundle_model;
    }
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');

        $favorite = $_SESSION['favorite'];
        $products = $this->bundle_model->getProductsFromArray($favorite);
        http_response_code(200);
        $this->setMeta("Favorite", "User's favorite products page", 'Favorite page, products, like');
        $this->setData(compact('cart_items_qty', 'currency', 'products'));
        $this->getView();
    }
}