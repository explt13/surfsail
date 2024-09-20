<?php
namespace app\widgets\cart;

use app\models\ProductModel;
use nosmi\App;

class Cart
{
    private $cart = [];
    private $products;
    private $items_count;

    public function __construct()
    {
        $this->setCart();
        echo $this->render();
    }

    private function render()
    {
        ob_start();
        require_once __DIR__.'/tpl/cart_tpl.php';
        return ob_get_clean();
    }
    private function setCart()
    {
        $cart = [];
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true) ?? [];
        }
        $this->items_count = count($cart);
    }
}