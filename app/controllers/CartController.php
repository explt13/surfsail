<?php

namespace app\controllers;

use app\models\CartModel;
use app\models\interfaces\CartModelInterface;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\OrderModelInterface;
use app\models\OrderModel;
use nosmi\App;

class CartController extends BundleController
{   
    protected $order_model;

    public function __construct(
        CartModelInterface $bundle_model,
        OrderModelInterface $order_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($bundle_model, $currency_model, $category_model);
        $this->order_model = $order_model; 
    }

    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $cart_items_qty = $this->bundle_model->getItemsQty();
        $products = $this->bundle_model->getProductsFromArray();
        http_response_code(200);
        $this->setMeta("Cart", "User's cart page", 'Cart, page, products, buy, order');
        $this->setData(compact('cart_items_qty', 'currency', 'products'));
        $this->getView();
    }
    
    public function buyAction()
    {
        $this->order_model->saveOrder();
    }
}