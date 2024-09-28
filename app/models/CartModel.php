<?php
namespace app\models;

class CartModel extends AppModel
{
    public function initializeCart()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    public function addProduct(bool|array $product, array $data)
    {
        if ($product === false) {
            return ["response_code" => 409, 'message' => 'No such product'];
        }
        $cart = &$_SESSION['cart'];

        if (!array_key_exists($data['product_id'], $cart)) {
            if ($data["qty"] <= $product['available_qty'] && $data["qty"] > 0) {
                $cart[$data['product_id']] = [
                    "product_id" => $data['product_id'],
                    "qty" => $data['qty'],
                    "added_date" => time(),
                ];
                return ['response_code' => 200, 'message' => 'Item added successfully', 'action' => 'add'];
            } else {
                return ['response_code' => 422, 'message' => "Out of stock", 'action' => 'add'];
            }
        } else {
            if ($data['qty_control']) {
                if (($cart[$data['product_id']]['qty'] + $data['qty']) > $product['available_qty']) {
                    return ['response_code' => 422, 'message' => "Out of stock", 'action' => 'add'];
                }
                $cart[$data['product_id']]['qty'] = $cart[$data['product_id']]['qty'] + $data['qty'];
                $cart[$data['product_id']]['added_date'] = time(); 
                return ['response_code' => 200, 'message' => 'Item added successfully', 'action' => 'add'];

            } else {
                unset($cart[$data['product_id']]);
                return ['response_code' => 200, 'message' => 'Item removed successfully', 'action' => 'remove'];
            }
        }
    }

    public function getCartProductsIds()
    {
        return array_keys($_SESSION['cart']);
    }

    public function deleteProduct(int $product_id)
    {
        $cart = &$_SESSION['cart'];
        if (array_key_exists($product_id, $cart)) {
            unset($cart[$product_id]);
            return ["response_code" => 200, "message" => "Product has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such product in cart"];
        }
    }
}