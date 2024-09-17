<?php

namespace app\controllers;

class CartController extends AppController
{
    private array $cart = [];

    public function indexAction()
    {
        $cart = $this->cart;
        $this->setData(compact('cart'));
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
        if (!in_array($data['product_id'], $cart)) {
            $cart[] = $data['product_id'];
            $msg = 'Item added successfully';
            $action = 'add';
        } else {
            if (($key = array_search($data['product_id'], $cart)) !== false) {
                unset($cart[$key]);
                $msg = 'Item removed successfully';
                $action = 'remove';
            }
        }
        setcookie('cart', json_encode($cart), time() + 3600 * 24 * 7, '/');
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => $msg, 'action' => $action]);
    }
}