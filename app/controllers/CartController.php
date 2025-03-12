<?php

namespace app\controllers;

use app\models\interfaces\CartModelInterface;
use app\models\interfaces\OrderModelInterface;
use nosmi\App;
use nosmi\base\Controller;

class CartController extends Controller
{   
    protected $order_model;
    protected $cart_model;

    public function __construct(
        CartModelInterface $cart_model,
        OrderModelInterface $order_model,
    )
    {
        $this->order_model = $order_model; 
        $this->cart_model = $cart_model; 
    }

    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $cart_items_qty = $this->cart_model->getProductsQty();
        $products = $this->cart_model->getProductsFromArray();
        http_response_code(200);
        $this->setMeta("Cart", "User's cart page", 'Cart, page, products, buy, order');
        $this->render(data: compact('cart_items_qty', 'currency', 'products'));
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->cart_model->addProduct($data);

        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function addMultipleAction()
    {
        header('Content-Type: application/json');
        $data = $this->request->getPostData();
        $result = $this->cart_model->addMultipleProducts($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }

    public function getAddedItemsAction()
    {
        header('Content-Type: application/json');
        if (isset($_SESSION['user'])) {
            $products_ids = $this->cart_model->getAddedProductsIds();
        } else {
            $products_ids = [];
        }
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->cart_model->deleteProduct($data['item_id']);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
    
    public function buyAction()
    {
        $this->order_model->saveOrder();
    }
}