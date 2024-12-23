<?php
namespace app\widgets\cart;

use app\models\interfaces\CartModelInterface;
use app\models\ProductModel;
use nosmi\base\Widget;
use nosmi\App;

class Cart extends Widget
{
    protected $items_count;

    public function __construct()
    {
        $this->items_count = App::$registry->getProperty('cart_items_qty') ?? 0;
        $this->tpl = __DIR__.'/tpl/cart_tpl.php';
    }
}