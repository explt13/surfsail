<?php
namespace app\models;

use app\controllers\BundleController;

abstract class BundleModel extends AppModel
{
    public function initializeBundle()
    {
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = [];
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

    public function getProductsFromArray(array $array)
    {
        if (!empty($array)) {
            $product_model = new ProductModel();
            $ids = array_keys($array);
            $result = $product_model->getProducts(["id" => $ids]);
    
            foreach ($result as &$res) {
                $product_id = $res['id'];
                $res['qty'] = $array[$product_id]['qty'];
                $res['added_date'] = $array[$product_id]['added_date'];
            }
    
            usort($result, fn($a, $b) => $b['added_date'] - $a['added_date']);
            return $result;
        }
        return false;
    }
}