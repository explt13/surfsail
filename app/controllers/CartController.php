<?php

namespace app\controllers;

use app\models\CartModel;
use app\models\ProductModel;
use nosmi\App;

class CartController extends AppController
{
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $cart_items_qty = App::$registry->getProperty('cart_items_qty');
        $cart_model = new CartModel();

        $cart = $_SESSION['cart'];
        $products = $cart_model->getProductsFromCart($cart);
        http_response_code(200);
        $this->setData(compact('cart_items_qty', 'currency', 'products'));
        $this->getView();
    }

    public function getCartProductsAction()
    {
        header('Content-Type: application/json');
        $cart_model = new CartModel();
        $products_ids = $cart_model->getCartProductsIds();
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $products_model = new ProductModel();
        $cart_model = new CartModel();

        $product = $products_model->getProducts(['id' => $data['product_id']], 1);
        $result = $cart_model->addProduct($product, $data);
        
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function buyAction()
    {
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $cart_model = new CartModel();
        $result = $cart_model->deleteProduct($data['product_id']);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
}