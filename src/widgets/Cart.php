<?php
namespace Surfsail\widgets;

use Explt13\Nosmi\base\Widget;

class Cart extends Widget
{
    protected $items_count;

    public function __construct()
    {
        $this->tpl = __DIR__ . '/tpl/cart.php';
        $this->items_count = $_SESSION['cart_count'] ?? 0;
    }
}