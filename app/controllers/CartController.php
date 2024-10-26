<?php

namespace app\controllers;

use app\models\CartModel;
use app\models\ProductModel;
use nosmi\App;

class CartController extends BundleController
{    
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $cart_items_qty = App::$registry->getProperty('cart_items_qty');
        $product_model = new ProductModel();

        $cart = $_SESSION['cart'];
        $products = $product_model->getProductsFromArray($cart);
        http_response_code(200);
        $this->setMeta("Cart", "User's cart page", 'Cart, page, products, buy, order');
        $this->setData(compact('cart_items_qty', 'currency', 'products'));
        $this->getView();
    }
    

    public function buyAction()
    {
    }
}