<?php
namespace Surfsail\models;

use Explt13\Nosmi\base\Model;

abstract class AppModel extends Model
{

    public function __construct()
    {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['cart_count'])) $_SESSION['cart_count'] = 0;
        if (!isset($_SESSION['favorite'])) $_SESSION['favorite'] = [];
        if (!isset($_SESSION['favorite']['products'])) $_SESSION['favorite']['products'] = [];
        if (!isset($_SESSION['favorite']['articles'])) $_SESSION['favorite']['article'] = [];
        parent::__construct();
    }
    protected function setDefinedAttributes($data){
        foreach ($this->attributes as $k => $v) {
            if (isset($data[$k])) {
                $this->attributes[$k] = $data[$k];
            }
        }
    }
}