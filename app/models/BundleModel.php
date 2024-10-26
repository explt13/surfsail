<?php
namespace app\models;

use app\controllers\BundleController;

class BundleModel extends AppModel
{
    private string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public static function initializeBundle(string $name)
    {
        if (!isset($_SESSION[$name])) {
            $_SESSION[$name] = [];
        }
    }
    public function getProductsIds()
    {
        return array_keys($_SESSION[$this->name]);
    }

    public function deleteProduct(int $product_id)
    {
        $bundle = &$_SESSION[$this->name];
        if (array_key_exists($product_id, $bundle)) {
            unset($bundle[$product_id]);
            return ["response_code" => 200, "message" => "Product has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such product in $this->name"];
        }
    }
}