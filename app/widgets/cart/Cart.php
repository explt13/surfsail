<?php
namespace app\widgets\cart;

use app\models\ProductModel;
use nosmi\App;

class Cart
{
    private $items_count;

    public function __construct()
    {
        $this->items_count = App::$registry->getProperty('cart_items_qty');
        echo $this->render();
    }

    private function render()
    {
        ob_start();
        require_once __DIR__.'/tpl/cart_tpl.php';
        return ob_get_clean();
    }
    public static function getCartQty()
    {
        $cart = $_SESSION['cart'];
        return array_reduce($cart, fn($a, $b) => $a + $b['qty'], 0);
    }
}