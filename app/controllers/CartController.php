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
        if (!$products){
            $msg = "Cart is empty";
        }
        $this->setData(compact('currency', 'products'));
        $this->getView();
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $cart = [];
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
        }
        if (!key_exists($data['product_id'], $cart)) {
            $cart[$data['product_id']] = $data["qty"];
            $msg = 'Item added successfully';
            $action = 'add';
        } else {
            if ($cart[$data['product_id']]) {
                unset($cart[$data['product_id']]);
                $msg = 'Item removed successfully';
                $action = 'remove';
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No such product']);
            }
        }
        setcookie('cart', json_encode($cart), time() + 3600 * 24 * 7, '/');
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => $msg, 'action' => $action]);
    }

    public function buyAction()
    {
        
    }
}