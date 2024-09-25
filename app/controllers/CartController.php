<?php

namespace app\controllers;

use app\models\ProductModel;
use nosmi\App;

class CartController extends AppController
{
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $products_model = new ProductModel();
        $cart = [];
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true) ?? [];
        }
        $products = $products_model->getProductsByIds($cart);
        http_response_code(200);
        $this->setData(compact('currency', 'products'));
        $this->getView();
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $products_model = new ProductModel();
        $cart = [];
        $product = $products_model->getProductById($data['product_id']);
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
        }
        if (!key_exists($data['product_id'], $cart) && !$product === false) {
            if ($product['qty'] >= $data["qty"]) {
                $cart[$data['product_id']] = $data["qty"];
                $msg = 'Item added successfully';
                $action = 'add';
            } else {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => "Out of stock. Only {$product['qty']} left"]);
                die();
            }
        } else {
            if ($data['fromView'] && $cart[$data['product_id']]) {
                $cart[$data['product_id']] = $cart[$data['product_id']] + $data['qty'];
                $action = "add";
                $msg = 'Item added successfully';
            } else {
                if ($cart[$data['product_id']]) {
                    unset($cart[$data['product_id']]);
                    $msg = 'Item removed successfully';
                    $action = 'remove';
                } else {
                    http_response_code(409);
                    echo json_encode(['success' => false, 'message' => 'No such product']);
                    die();
                }
            }
        }
        setcookie('cart', json_encode($cart), time() + 3600 * 24 * 7, '/');
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => $msg, 'action' => $action]);
    }

    public function buyAction()
    {
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $cart_products = json_decode($_COOKIE['cart'] ?? "[]", true);
        if (array_key_exists($data['product_id'], $cart_products)) {
            unset($cart_products[$data['product_id']]);
            setcookie("cart", json_encode($cart_products), time() + 3600 * 24 * 7, '/');
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Product has been removed"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "failure", "message" => "No such product in cart"]);
        }
    }
}