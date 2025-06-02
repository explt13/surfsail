<?php
namespace Surfsail\models;

use Surfsail\interfaces\CartModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Explt13\Nosmi\App;

class CartModel extends AppModel implements CartModelInterface
{   
    private ProductModelInterface $product_model;

    public function __construct(ProductModelInterface $product_model)
    {
        parent::__construct();
        $_SESSION['cart_count'] = $this->getCartCount();
        $this->product_model = $product_model;
    }

    public function getCartCount(): int
    {
        $cart = $_SESSION['cart'];
        if (empty($cart)) {
            return 0;
        }
        return count($cart);
    }

    public function getProductsQty(): int
    {
        $cart = $_SESSION['cart'];
        if (empty($cart)) {
            return 0;
        }
        return array_reduce($cart, fn($a, $b) => $a + $b['qty'], 0);
    }

    public function getAddedProductsIds(): array
    {
        return array_keys($_SESSION['cart']);
    }

    public function deleteProduct(int $product_id)
    {
        $cart = &$_SESSION['cart'];
        if (array_key_exists($product_id, $cart)) {
            unset($cart[$product_id]);
            --$_SESSION['cart_count'];
            return ["response_code" => 200, "message" => "Product has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such product in 'cart'"];
        }
    }

    public function getProductsFromArray(): array
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
        return [];
    }

    public function addProduct(array $data)
    {
        $product = $this->product_model->getProducts(['id' => $data['product_id']], 1);

        if ($product === false) return ["response_code" => 409, 'message' => 'No such product'];

        $cart = &$_SESSION['cart'];

        if (isset($cart[$data['product_id']])) {
            $cart_product = &$cart[$data['product_id']];
        } else {
            $cart_product = null;
        }
        
        if ($data["qty"] > 0 && ($data["qty"] <= $product['available_qty'])) {
            // if a product hasn't been set in the cart yet
            if (is_null($cart_product)) {
                $cart[$data['product_id']] = [
                    "product_id" => $data['product_id'],
                    "qty" => $data['qty'],
                    "added_date" => time(),
                ];
                ++$_SESSION['cart_count'];
                return ['response_code' => 200, 'message' => 'Product added successfully'];
            } else {
                if ($data['qty'] === $cart_product['qty']) {
                    return;
                }
                if ($data['qty'] < $cart_product['qty']) {
                    $message = 'Product removed successfully';
                } else {
                    $message = 'Product added successfully';
                }
                $cart_product['qty'] = $data['qty'];
                if ($data['update_time']) {
                    $cart_product['added_date'] = time();
                }
                return ['response_code' => 200, 'message' => $message];
            }
            
        } else {
            return ['response_code' => 422, 'message' => "Out of stock"];
        }
    }
}