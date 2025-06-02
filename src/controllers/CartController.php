<?php

namespace Surfsail\controllers;

use Surfsail\interfaces\CartModelInterface;
use Surfsail\interfaces\OrderModelInterface;
use Explt13\Nosmi\App;
use Explt13\Nosmi\base\Controller;
use Nyholm\Psr7\Factory\Psr17Factory;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;

class CartController extends BaseController
{   
    protected $order_model;
    protected $cart_model;
    private CurrencyService $currency_service;


    public function __construct(
        CurrencyService $currency_service,
        CartModelInterface $cart_model,
        OrderModelInterface $order_model,
    )
    {
        parent::__construct();
        $this->order_model = $order_model; 
        $this->cart_model = $cart_model;
        $this->currency_service = $currency_service;
    }

    public function indexAction()
    {
        $currency = $this->currency_service->getCurrencyByCookie();
        $cart_items_qty = $this->cart_model->getProductsQty();
        $products = $this->cart_model->getProductsFromArray();
        $html = $this->getView()->withMetaArray([
            "title" => "Cart",
            "description" => "User's cart page",
            "keywords" => "Cart, page, products, buy, order"
        ])->render($this->getRoute()->getAction(), compact('cart_items_qty', 'currency', 'products'));
        $this->response = $this->response->withStatus(200)->withHtml($html);
    }

    public function post()
    {
        $data = $this->request->getParsedBody();
        $result = $this->cart_model->addProduct($data);

        $this->response = $this->response
                               ->withStatus($result['response_code'])
                               ->withJson(['message' => $result['message']]);
    }

    public function patch()
    {
        $data = $this->request->getParsedBody();
        $result = $this->cart_model->addProduct($data);

        $this->response = $this->response
                               ->withStatus($result['response_code'])
                               ->withJson(['message' => $result['message']]);
    }

    public function get()
    {
        if (isset($_SESSION['cart'])) {
            $products_ids = $this->cart_model->getAddedProductsIds();
        } else {
            $products_ids = [];
        }
        $this->response = $this->response->withStatus(200)->withJson($products_ids);
    }

    public function delete()
    {
        $product_id = $this->getRoute()->getParam('product_id');
        $result = $this->cart_model->deleteProduct($product_id);
        $this->response = $this->response->withStatus($result['response_code'])->withJson(["message" => "Product has been removed"]);
    }
    
    public function buyAction()
    {
        $user = $this->request->getSession('user');
        $currency = $this->currency_service->getCurrencyByCookie();
        $this->order_model->saveOrder($user, $currency);
        $this->response = $this->response->withRedirect($this->request->getUri()->getPath(), 303);
    }
}