<?php
namespace app\models;

use app\models\interfaces\CartModelInterface;
use app\models\interfaces\ProductModelInterface;

class CartModel extends BundleModel implements CartModelInterface
{   
    protected string $name = 'cart';
    public function __construct(ProductModelInterface $product_model)
    {
        parent::__construct($product_model);
    }
    public function addProduct(array $data)
    {
        $product = $this->product_model->getProducts(['id' => $data['product_id']], 1);
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
            if ($data['qty_control']){
                if ($data['action'] === 'addup') {
                    if (($cart[$data['product_id']]['qty'] + $data['qty']) > $product['available_qty']) {
                        return ['response_code' => 422, 'message' => "Out of stock", 'action' => 'add'];
                    }   
                    $cart[$data['product_id']]['qty'] = $cart[$data['product_id']]['qty'] + $data['qty'];
                    if ($data['update_time']) {
                        $cart[$data['product_id']]['added_date'] = time();
                    } else {
                        $cart[$data['product_id']]['added_date'] = $cart[$data['product_id']]['added_date'] ?? time();
                    }
                    return ['response_code' => 200, 'message' => 'Item added successfully', 'action' => 'add'];
                } else if ($data['action'] === 'actual') {
                    if (($cart[$data['product_id']]['qty'] - 1) <= 0) {
                        return ['response_code' => 422, 'message' => "Cannot remove last item", 'action' => 'remove'];
                    }   
                    $cart[$data['product_id']]['qty'] = $data['qty'];
                    return ['response_code' => 200, 'message' => 'Item removed successfully', 'action' => 'remove'];
                }
            } else {
                unset($cart[$data['product_id']]);
                return ['response_code' => 200, 'message' => 'Item removed successfully', 'action' => 'remove'];
            }
        }
    }
}