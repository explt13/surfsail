<?php
namespace app\widgets\cart;


use nosmi\base\Widget;
use nosmi\App;

class Cart extends Widget
{
    protected $items_count;

    public function __construct()
    {
        parent::__construct(null);
        $this->items_count = App::$registry->getProperty('cart_items_qty') ?? 0;
    }
}