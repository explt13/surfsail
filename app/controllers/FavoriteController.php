<?php
namespace app\controllers;

use app\models\FavoriteModel;
use app\models\ProductModel;
use nosmi\App;

class FavoriteController extends BundleController
{
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $product_model = new ProductModel();

        $favorite = $_SESSION['favorite'];
        $products = $product_model->getProductsFromArray($favorite);
        http_response_code(200);
        $this->setMeta("Favorite", "User's favorite products page", 'Favorite page, products, like');
        $this->setData(compact('cart_items_qty', 'currency', 'products'));
        $this->getView();
    }
 
}