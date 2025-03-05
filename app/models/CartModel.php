<?php
namespace app\models;

use app\models\interfaces\CartModelInterface;
use app\models\interfaces\ProductModelInterface;
use nosmi\App;

class CartModel extends AppModel implements CartModelInterface
{   
    private ProductModelInterface $product_model;

    public function __construct(ProductModelInterface $product_model)
    {
        parent::__construct();
        $this->product_model = $product_model;
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    }

    public function getProductsQty()
    {
        $bundle = $_SESSION['cart'];
        if (App::$registry->getProperty('loggedIn') === false) {
            return 0;
        }
        return array_reduce($bundle, fn($a, $b) => $a + $b['qty'], 0);
    }

    public function getAddedProductsIds()
    {
        return array_keys($_SESSION['cart']);
    }

    public function deleteProduct(int $product_id)
    {
        $bundle = &$_SESSION['cart'];
        if (array_key_exists($product_id, $bundle)) {
            unset($bundle[$product_id]);
            return ["response_code" => 200, "message" => "Product has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such product in 'cart'"];
        }
    }

    public function getProductsFromArray()
    {
        $array = $_SESSION['cart'];
        if (!empty($array)) {
            $ids = array_keys($array);
            $result = $this->product_model->getProducts(["id" => $ids]);
    
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

    public function addProduct(array $data)
    {
        $product = $this->product_model->getProducts(['id' => $data['product_id']], 1);
        if ($product === false) {
            return ["response_code" => 409, 'message' => 'No such product'];
        }
        $cart = &$_SESSION['cart'];
        if (!array_key_exists($data['product_id'], $cart)) {
            if ($data["qty"] > 0 && $data["qty"] <= $product['available_qty']) {
                $cart[$data['product_id']] = [
                    "product_id" => $data['product_id'],
                    "qty" => $data['qty'],
                    "added_date" => time(),
                ];
                return ['response_code' => 200, 'message' => 'Product added successfully', 'action' => 'add'];
            } else {
                return ['response_code' => 422, 'message' => "Out of stock", 'action' => 'add'];
            }
        } else {
            unset($cart[$data['product_id']]);
            return ['response_code' => 200, 'message' => 'Item removed successfully', 'action' => 'remove'];
        }
    }

    public function addMultipleProducts(array $data)
    {
        $mods = ['addup', 'direct_control'];
        $product = $this->product_model->getProducts(['id' => $data['product_id']], 1);
        if ($product === false) return ["response_code" => 409, 'message' => 'No such product'];

        $cart = &$_SESSION['cart'];
        $the_product = null;
        if (isset($cart[$data['product_id']])) $the_product = &$cart[$data['product_id']];
        
        if (is_null($the_product)) {
            if ($data["qty"] > 0 && ($data["qty"] <= $product['available_qty'])) {
                $the_product = [
                    "product_id" => $data['product_id'],
                    "qty" => $data['qty'],
                    "added_date" => time(),
                ];
                $cart[$data['product_id']] = $the_product;
                return ['response_code' => 200, 'message' => 'Product added successfully'];
            } else {
                return ['response_code' => 422, 'message' => "Out of stock"];
            }
        }
        if (!in_array($data['mode'], $mods)) return ['resposne_code' => 409, 'Invalid operation'];

        if ($data['mode'] === 'addup') {
            if (($the_product['qty'] + $data['qty']) > $product['available_qty']) {
                return ['response_code' => 422, 'message' => "Out of stock"];
            }
            $the_product['qty'] = $the_product['qty'] + $data['qty'];
        }

        if ($data['mode'] === 'direct_control') {
            if (($data['qty'] <= 1) || ($data['qty'] > $product['available_qty'])) {
                return ['response_code' => 422, 'message' => "Out of stock"];
            }
            $the_product['qty'] = $data['qty'];
        }

        if ($data['update_time']) {
            $the_product['added_date'] = time();
        }

        return ['response_code' => 200, 'message' => 'Item added successfully'];
    }
}